<?php

namespace App\Http\Controllers;

use App\CustomerRequestStage;

class CustomerRequestController extends Controller
{
    protected $rules = [
        'opportunity_id' => 'integer',
        'service_region' => 'string',
        'frequency_band' => 'string',
        'mbit_mhz' => 'string',
        'details' => 'string',
        'title' => 'string'
    ];
    protected $customer_request;

    public function __construct(CustomerRequestStage $customer_request)
    {
        parent::__construct($customer_request, $this->rules);
        $this->customer_request = $customer_request;
    }
}
