<?php

namespace App\Listeners;

use App\Events\DateWatcherEvent;

class DateWatcherListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  DateWatcherEvent $event
     * @return void
     */
    public function handle(DateWatcherEvent $event)
    {
        mail(
            'azhdar.shirinzade@azercosmos.az',
            $event->instance->informSubject(),
            $event->instance->informBody(),
            'ajdar.shirinzada@gmail.com'
        );
    }
}
