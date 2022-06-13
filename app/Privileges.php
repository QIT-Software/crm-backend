<?php

namespace App;

class Privileges extends Model
{
    public $timestamps = false;
    protected $connection = 'mysql2';
    protected $table = 'crm_priv';

    protected $fillable = [
        'people_id', 'edit', 'admin'
    ];
}