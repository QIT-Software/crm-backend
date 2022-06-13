<?php

namespace App;

class RequestLog extends Model
{
    protected $table = 'request_logs';

    protected $fillable = [
        'log_id', 'before', 'after',
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];
}