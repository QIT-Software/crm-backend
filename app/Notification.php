<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use SoftDeletes;

    protected $table = 'notifications';

    protected $fillable = [
        'user_id', 'message', 'read'
    ];

    protected $dates = [
        'deleted_at'
    ];
}