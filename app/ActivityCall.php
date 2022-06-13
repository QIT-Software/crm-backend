<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class ActivityCall extends Model
{
    use SoftDeletes;

    protected $table = 'activity_calls';

    protected $fillable = [
        'subject', 'comments', 'account_id', 'contact_id'
    ];
    protected $dates = [
        'deleted_at'
    ];

    public function selectByAccountId($id)
    {
        $result = DB::table($this->table)
            ->groupBy("{$this->table}.id")
            ->join('contacts', "{$this->table}.contact_id", 'contacts.id')
            ->leftJoin('related_activities', function ($query) {
                $query->on('related_activities.activity_id', "{$this->table}.id")
                    ->where('related_activities.type', 'call');
            })
            ->join("{$this->erp}.PEOPLE', 'PEOPLE.ID', 'related_activities.related_to")
            ->whereNull("{$this->table}.deleted_at")
            ->where("{$this->table}.account_id", '=', $id)
            ->select(
                "{$this->table}.subject", "{$this->table}.comments",
                'contacts.name', DB::raw("
                    group_concat(PEOPLE.NAME separator ', ') as related_to
                ")
            )
            ->get();

        return response()->json($result);
    }
}
