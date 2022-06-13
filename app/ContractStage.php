<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class ContractStage extends Model
{
    use SoftDeletes;

    protected $table = 'contract_stage';

    protected $fillable = [
        'opportunity_id', 'reference_number', 'date_of_conclusion',
        'date_of_expiry', 'date_of_ammendments'
    ];

    protected $hidden = [
        'updated_at', 'deleted_at'
    ];

    protected $dates = [
        'deleted_at'
    ];
}