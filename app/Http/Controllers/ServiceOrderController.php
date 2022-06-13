<?php

namespace App\Http\Controllers;

use App\ServiceOrder;

class ServiceOrderController extends Controller
{
    protected $rules = [
        'opportunity_id' => 'integer',
        'reference_number' => 'integer',
        'date_of_issue' => 'date',
        'date_of_service' => 'date',
        'date_of_amendments' => 'date',
        'amount' => 'integer',
        'title' => 'string'
    ];
    protected $service_order;

    public function __construct(ServiceOrder $service_order)
    {
        parent::__construct($service_order, $this->rules);
        $this->service_order = $service_order;
    }

    public function selectByOpportunityId($id)
    {
        $result = ServiceOrder::where('opportunity_id', $id)
            ->latest()
            ->get(['title']);

        return response()->json($result);
    }
}