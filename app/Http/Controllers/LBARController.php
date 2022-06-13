<?php

namespace App\Http\Controllers;

use App\LinkBudgetAnalysisStage;
use Schema;

class LBARController extends Controller
{
    protected $rules = [
        'opportunity_id' => 'integer',
        'allocated_bandwidth' => 'string',
        'data_rate' => 'string',
        'downlink_location' => 'string',
        'uplink_location' => 'string',
        'modem_model' => 'string',
        'satellite_name' => 'string',
        'recommended_hpa_size' => 'string',
        'lost_reason' => 'nullable|string',
        'fail_note' => 'nullable|string',
        'title' => 'string'
    ];
    protected $lbar;

    public function __construct(LinkBudgetAnalysisStage $lbar)
    {
        parent::__construct($lbar, $this->rules);
        $this->lbar = $lbar;
    }

    public function selectByOpportunityId($id)
    {
        $result = LinkBudgetAnalysisStage::where([
                ['opportunity_id', $id],
                ['lost_reason', null]
            ])
            ->latest()
            ->get(['title']);

        return response()->json($result);
    }
}
