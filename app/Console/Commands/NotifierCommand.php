<?php

namespace App\Console\Commands;

use App\Notification;
use App\Invoice;
use App\DemoFormStage;
use App\ActivityEvent;
use App\ActivityTask;
use App\Events\DateWatcherEvent;
use App\Contracts\Notifiable;
use Illuminate\Console\Command;

class NotifierCommand extends Command
{
    protected $signature = 'check:dates';
    protected $description = 'Daily events notifier';

    private $invoice;
    private $demo_form;
    private $event;
    private $task;

    public function __construct()
    {
        parent::__construct();
        $this->setUpWatchableInstances();
    }

    public function handle()
    {
        $this->todaysInvoices();
        $this->finalDayOfFreeUsage();
        $this->endOfEvents();
        $this->endOfTasks();
    }

    private function setUpWatchableInstances()
    {
        $this->invoice = Invoice::query();
        $this->demo_form = DemoFormStage::query();
        $this->event = ActivityEvent::query();
        $this->task = ActivityTask::query();
    }

    private function todaysInvoices()
    {
        $invoices = $this->invoice
            ->whereRaw('date(due_date) = curdate()')
            ->orWhereRaw('date(overdue_date) = curdate()')
            ->get();

        $this->invokeEvent($invoices);
    }

    private function finalDayOfFreeUsage()
    {
        $demos = $this->demo_form->whereRaw('date(end) = curdate()')->get();

        $this->invokeEvent($demos);
    }

    private function endOfEvents()
    {
        $events = $this->event->whereRaw('date(end) = curdate()')->get();

        $this->invokeEvent($events);
    }

    private function endOfTasks()
    {
        $tasks = $this->task->whereRaw('date(due_date) = curdate()')->get();

        $this->invokeEvent($tasks);
    }

    private function invokeEvent($watchable)
    {
        if ($watchable->isEmpty()) {
            return;
        }

        $watchable->each(function ($item) {
            if ($item instanceof Notifiable) {
                $this->notify($item);
            }
            event(new DateWatcherEvent($item));
        });
    }

    private function notify($item)
    {
        $item->owners()->each(function ($owner) use($item) {
            Notification::create([
                'user_id' => $owner->ID,
                'message' => $item->message()
            ]);
        });
    }
}