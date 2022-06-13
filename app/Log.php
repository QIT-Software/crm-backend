<?php

namespace App;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class Log extends Model
{
    protected $table = 'logs';

    protected $fillable = [
        'user_id', 'action_id', 'module_id', 'object_id',
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function selectAll()
    {
        $logs = DB::table($this->table)
            ->leftJoin('request_logs', 'logs.id', '=', 'request_logs.log_id')
            ->leftJoin('logging_modules', 'logs.module_id', '=', 'logging_modules.id')
            ->leftJoin('logging_actions', 'logs.action_id', '=', 'logging_actions.id')
            ->leftJoin("{$this->erp}.PEOPLE", 'logs.user_id', '=', "{$this->erp}.PEOPLE.id")
            ->select("{$this->erp}.PEOPLE.name as user", 'logging_actions.action_name as action', 'logging_modules.module_name as module', 'logs.created_at as date')
            ->orderByDesc('logs.created_at')
            ->get();

        return response()->json($logs);
    }

    public function getFilteredLogs(Request $request)
    {
        $filtered_logs = DB::table($this->table)
            ->leftJoin('request_logs', 'logs.id', '=', 'request_logs.log_id')
            ->leftJoin('logging_modules', 'logs.module_id', '=', 'logging_modules.id')
            ->leftJoin('logging_actions', 'logs.action_id', '=', 'logging_actions.id')
            ->leftJoin("{$this->erp}.PEOPLE", 'logs.user_id', '=', "{$this->erp}.PEOPLE.id")
            ->select("{$this->erp}.PEOPLE.name as user", 'logging_actions.action_name as action', 'logging_modules.module_name as module', 'logs.created_at as date')
            ->where($request->all())
            ->orderByDesc('logs.created_at')
            ->get();

        return response()->json($filtered_logs);
    }
}
