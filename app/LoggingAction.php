<?php

namespace App;

use Illuminate\Support\Facades\DB;

class LoggingAction extends Model
{
    protected $table = 'logging_actions';

    protected $fillable = [
        'action_name',
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    static function getActions()
    {
       return DB::table('logging_actions')->get();
    }
}