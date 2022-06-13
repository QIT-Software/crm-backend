<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Contracts\Watchable;
use App\Contracts\Notifiable;
use DB;

class ActivityEvent extends Model implements Watchable, Notifiable
{
    use SoftDeletes;

    protected $table = 'activity_events';

    protected $fillable = [
        'subject', 'description', 'account_id',
        'start', 'end', 'location', 'contact_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
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
                    ->where('related_activities.type', 'event');
            })
            ->join("contacts", "contact_id", "contacts.id")
            ->leftJoin("{$this->erp}.PEOPLE', 'PEOPLE.ID', 'related_activities.related_to")
            ->whereNull("{$this->table}.deleted_at")
            ->whereNull("contacts.deleted_at")
            ->where("{$this->table}.account_id", "=", $id)
            ->select(
                "{$this->table}.subject", "{$this->table}.description",
                "{$this->table}.end", "contacts.name", "contacts.id as contact_id",
                "{$this->table}.start", "{$this->table}.location", DB::raw("
                    group_concat(PEOPLE.NAME separator ', ') as related_to
                ")
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
                    ->where('related_activities.type', 'event');
            })
            ->join("accounts", "{$this->table}.account_id", "accounts.id")
            ->join("contacts", "{$this->table}.contact_id", "contacts.id")
            ->join("{$this->erp}.PEOPLE", "PEOPLE.ID", "related_activities.related_to")
            ->whereNull("{$this->table}.deleted_at")
            ->whereNull("accounts.deleted_at")
            ->whereNull("contacts.deleted_at")
            ->select(
                "{$this->table}.id as event_id", "{$this->table}.subject as title",
                "{$this->table}.description", "{$this->table}.start", "contacts.id as contact_id",
                "{$this->table}.end", "{$this->table}.location", "contacts.name as contact",
                "accounts.company_name as account", DB::raw("
                    group_concat(PEOPLE.NAME separator ', ') as related_to
                ")
            )
            ->get();

        return response()->json($result);
    }

    public function informSubject()
    {
        return 'The end date of event has come.';
    }

    public function informBody()
    {
        $body = "Company Name: <strong>{$this->account->company_name}</strong>\n";
        $body .= "Contact: <strong>{$this->contact->full_name}</strong>\n\n";
        $body .= "Parameters:\n";
        $body .= "\t1. Subject: <strong>{$this->subject}</strong>\n";
        $body .= "\t2. Description: <strong>{$this->description}</strong>\n";
        $body .= "\t3. Start Datetime: <strong>{$this->start}</strong>\n";
        $body .= "\t4. End Datetime: <strong>{$this->end}</strong>\n";
        $body .= "\t5. Location: <strong>{$this->location}</strong>\n";
        $body .= "\t6. Related to: <strong>{$this->manager($this->related_to)}</strong>";

        return $body;
    }

    public function message()
    {
        $msg = "The deadline of event: <strong>{$this->subject}</strong> ";
        $msg .= "(<strong>{$this->account->company_name}</strong>) has come.";

        return $msg;
    }

    private function manager($id)
    {
        return People::where('ID', $id)->pluck('NAME')[0];
    }

    public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function contact()
    {
        return $this->belongsTo('App\Contact');
    }

    public function owners()
    {
        return $this->account->managers;
    }
}
