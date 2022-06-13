<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Software extends Model
{
    use SoftDeletes;

    protected $table = 'softwares';

    protected $fillable = [
        'software', 'language_id'
    ];

    protected $dates = [
        'deleted_at'
    ];
}