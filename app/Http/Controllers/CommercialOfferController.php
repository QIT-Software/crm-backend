<?php

namespace App\Http\Controllers;

use App\CommercialOfferStage;
use Schema;

class CommercialOfferController extends Controller
{
    protected $rules = [
        'opportunity_id' => 'integer',
        'start' => 'date',
        'end' => 'date',
        'description' => 'string',
        'band' => 'string',
        'segment' => 'string',
        'type_of_service' => 'string',
        'volume' => 'integer',
        'unit' => 'string',
        'period_of_lease' => 'string',
        'service_start' => 'date',
        'service_end' => 'date',
        'notice_period' => 'string',
        'free_trial_time' => 'string',
        'other_conditions' => 'string',
        'payment_condition' => 'string',
        'further_notice' => 'string',
        'title' => 'string',
        'lost_reason' => 'nullable|string',
        'fail_note' => 'nullable|string'
    ];
    protected $offer;

    public function __construct(CommercialOfferStage $offer)
    {
        parent::__construct($offer, $this->rules);
        $this->offer = $offer;
    }

    public function selectByOpportunityId($id)
    {
        $result = CommercialOfferStage::where([
                ['opportunity_id', $id],
                ['lost_reason', null]
            ])
            ->latest()
            ->get(['title']);

        return response()->json($result);
    }
}