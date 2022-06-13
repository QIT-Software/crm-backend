<?php

namespace App\Http\Controllers;

use App\DemoFormStage;

class DemoFormController extends Controller
{
    protected $rules  = [
        'opportunity_id' => 'integer',
        'requested_period' => 'string',
        'date_rate' => 'date',
        'start' => 'date',
        'end' => 'date',
        'status' => 'string',
        'title' => 'string'
    ];
    protected $demo_form;

    public function __construct(DemoFormStage $demo_form)
    {
        parent::__construct($demo_form, $this->rules);
        $this->demo_form = $demo_form;
    }

    public function selectByOpportunityId($id)
    {
        $result = DemoFormStage::where('opportunity_id', $id)
            ->latest()
            ->get(['title']);

        return response()->json($result);
    }
}