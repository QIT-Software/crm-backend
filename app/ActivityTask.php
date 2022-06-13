<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Contracts\Watchable;
use App\Contracts\Notifiable;
use DB;

class ActivityTask extends Model implements Watchable, Notifiable
{
    use SoftDeletes;

    protected $table = 'activity_tasks';

    protected $fillable = [
        'subject', 'due_date', 'account_id',
        'contact_id', 'assigned_to'
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function selectByAccountId($id)
    {
        $result = DB::table($this->table)
            ->groupBy("{$this->table}.id")
            ->leftJoin('related_activities', function ($query) {
                $query->on('related_activities.activity_id', "{$this->table}.id")
                    ->where('related_activities.type', 'task');
            })
            ->join("contacts", "{$this->table}.contact_id", "contacts.id")
            ->join("accounts", "{$this->table}.account_id", "accounts.id")
            ->join("{$this->erp}.PEOPLE as r", "r.ID", "related_activities.related_to")
            ->join("{$this->erp}.PEOPLE as a", "a.ID", "{$this->table}.assigned_to")
            ->whereNull("{$this->table}.deleted_at")
            ->whereNull("contacts.deleted_at")
            ->whereNull("accounts.deleted_at")
            ->where("{$this->table}.account_id", "=", $id)
            ->select(
                "{$this->table}.subject", "{$this->table}.due_date",
                "contacts.name", "a.NAME as assigned_to", "contacts.id as contact_id",
                DB::raw("group_concat(r.NAME separator ', ') as related_to")
            )
            ->get();

        return response()->json($result);
    }

    public function selectAll()
    {
        $result = DB::table($this->table)
            ->groupBy("{$this->table}.id")
            ->leftJoin('related_activities', function ($query) {
                $query->on('related_activities.activity_id', "{$this->table}.id")
                    ->where('related_activities.type', 'task');
            })
            ->join("accounts", "accounts.id", "{$this->table}.account_id")
            ->join("contacts", "contacts.id", "{$this->table}.contact_id")
            ->join("{$this->erp}.PEOPLE as r", "r.ID", "related_activities.related_to")
            ->join("{$this->erp}.PEOPLE as a", "a.ID", "{$this->table}.assigned_to")
            ->whereNull("{$this->table}.deleted_at")
            ->whereNull("accounts.deleted_at")
            ->whereNull("contacts.deleted_at")
            ->select(
                "{$this->table}.subject as title", "{$this->table}.id as task_id",
                "accounts.company_name as account", "contacts.name as contact",
                "{$this->table}.due_date as start","a.NAME as assigned_to",
                "contacts.id as contact_id", DB::raw("group_concat(r.NAME separator ', ') as related_to")
            )
            ->get();

        return response()->json($result);
    }

    public function informSubject()
    {
        return 'The due date of task has come.';
    }

    public function informBody()
    {
        $body = "Company Name: <strong>{$this->account->company_name}</strong>\n";
        $body .= "Contact: <strong>{$this->contact->full_name}</strong>\n\n";
        $body .= "Parameters:\n";
        $body .= "\t1. Subject: <strong>{$this->subject}</strong>\n";
        $body .= "\t2. Due Date: <strong>{$this->due_date}</strong>\n";
        $body .= "\t3. Assigned to: <strong>{$this->manager($this->assigned_to)}</strong>\n";
        $body .= "\t4. Related to: <strong>{$this->manager($this->related_to)}</strong>";

        return $body;
    }

    public function message()
    {
        $msg = "The deadline of task: <strong>{$this->subject}</strong> ";
        $msg .= "(<strong>{$this->account->company_name}</strong>) has come.";

        return $msg;
    }

    public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function contact()
    {
        return $this->belongsTo('App\Contact');
    }

    public function manager($id)
    {
        return People::where('ID', $id)->pluck('NAME')[0];
    }

    public function owners()
    {
        return $this->account->managers;
    }
}
