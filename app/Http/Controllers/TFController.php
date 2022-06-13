<?php

namespace App\Http\Controllers;

use App\TechnicalFeasibilityStage;

class TFController extends Controller
{
    protected $rules = [
        'opportunity_id' => 'integer',
        'location' => 'string',
        'site_latitude' => 'string',
        'site_longitude' => 'string',
        'antenna_diameter' => 'string',
        'antenna_polarization' => 'string',
        'hpa_size' => 'string',
        'sat_equipment' => 'string',
        'inbound_data_rate' => 'string',
        'outbound_data_rate' => 'string',
        'note' => 'string',
        'title' => 'string'
    ];
    protected $tfr;

    public function __construct(TechnicalFeasibilityStage $tfr)
    {
        parent::__construct($tfr, $this->rules);
        $this->tfr = $tfr;
    }

    public function selectByOpportunityId($id)
    {
        $result = TechnicalFeasibilityStage::where('opportunity_id', $id)
            ->latest()
            ->get(['title']);

        return response()->json($result);
    }
}
