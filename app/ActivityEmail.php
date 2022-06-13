<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class ActivityEmail extends Model
{
    use SoftDeletes;

    protected $table = 'activity_emails';

    protected $fillable = [
        'from', 'to', 'bcc', 'account_id',
        'subject', 'content'
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function selectByAccountId($id)
    {
        $result = DB::table($this->table)
            ->groupBy("{$this->table}.id")
            ->join('accounts', 'account_id', 'accounts.id')
            ->leftJoin('related_activities', function ($query) {
                $query->on('related_activities.activity_id', "{$this->table}.id")
                    ->where('related_activities.type', 'email');
            })
            ->leftJoin("{$this->erp}.PEOPLE', 'PEOPLE.ID', 'related_activities.related_to")
            ->whereNull("{$this->table}.deleted_at")
            ->whereNull('accounts.deleted_at')
            ->where('activity_emails.account_id', '=', $id)
            ->select(
                'from', 'to', 'bcc', 'account_id',
                'subject', 'content', DB::raw("
                    group_concat(PEOPLE.NAME separator ', ') as related_to
                ")
            )
            ->get();

        return response()->json($result);
    }
}
