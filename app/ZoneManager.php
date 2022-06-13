<?php

namespace App;

class ZoneManager extends Model
{
    protected $table = 'zones_managers';

    protected $fillable = [
        'zone_id', 'manager_id'
    ];
}