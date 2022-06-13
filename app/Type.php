<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Type extends Model
{
    use SoftDeletes;

    protected $table = 'types';

    protected $fillable = [
        'type'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $dates = [
        'deleted_at'
    ];
 }
