<?php

namespace App\Http\Controllers;

use App\NegotiationStage;

class NegotiationController extends Controller
{
    protected $rules = [
        'opportunity_id' => 'integer',
        'status' => 'string',
        'declined_due_to' => 'string',
        'note' => 'string'
    ];
    protected $negotiation;

    public function __construct(NegotiationStage $negotiation)
    {
        parent::__construct($negotiation, $this->rules);
        $this->negotiation = $negotiation;
    }

    public function selectByOpportunityId($id)
    {
        $result = NegotiationStage::where('opportunity_id', $id)->get();

        return response()->json($result);
    }
}