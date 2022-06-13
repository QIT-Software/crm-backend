<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Carbon\Carbon;

class ServiceOrder extends Model
{
    use SoftDeletes;

    protected $table = 'service_order';

    protected $fillable = [
        'opportunity_id', 'reference_number', 'date_of_issue',
        'date_of_service', 'date_of_amendments', 'amount', 'title'
    ];

    protected $hidden = [
        'updated_at', 'deleted_at'
    ];

    protected $dates = [
        'deleted_at'
    ];
}