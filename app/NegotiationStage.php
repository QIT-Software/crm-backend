<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class NegotiationStage extends Model
{
    use SoftDeletes;

    protected $table = 'negotiation_stage';
    
    protected $fillable = [
        'opportunity_id', 'status', 'declined_due_to', 'note'
    ];

    protected $hidden = [
        'updated_at', 'deleted_at'
    ];
    
    protected $dates = [
        'deleted_at'
    ];
}