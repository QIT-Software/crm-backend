<?php

namespace App;

use Illuminate\Support\Facades\DB;

class LoggingModule extends Model
{
    protected $table = 'logging_modules';

    protected $fillable = [
        'module_name',
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    static function getModules()
    {
        return DB::table('logging_modules')->get();
    }
}