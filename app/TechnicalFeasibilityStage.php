<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class TechnicalFeasibilityStage extends Model
{
    use SoftDeletes;

    protected $table = 'technical_feasibility_stage';

    protected $fillable = [
        'opportunity_id', 'location', 'site_latitude', 'site_longitude',
        'antenna_polarization', 'hpa_size', 'sat_equipment', 'antenna_diameter',
        'inbound_data_rate', 'outbound_data_rate', 'note', 'title'
    ];

    protected $hidden = [
        'updated_at', 'deleted_at'
    ];

    protected $dates = [
        'deleted_at'
    ];
}
