<?php

namespace App\Http\Controllers;

use App\ContractStage;

class ContractController extends Controller
{
    protected $rules = [
        'opportunity_id' => 'integer',
        'reference_number' => 'integer',
        'date_of_conclusion' => 'date',
        'date_of_expiry' => 'date',
        'date_of_ammendments' => 'date'
    ];
    protected $contract;

    public function __construct(ContractStage $contract)
    {
        parent::__construct($contract, $this->rules);
        $this->contract = $contract;
    }
}