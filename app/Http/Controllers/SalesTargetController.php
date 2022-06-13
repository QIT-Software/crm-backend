<?php

namespace App\Http\Controllers;

use App\SalesTarget;
use Illuminate\Http\Request;

class SalesTargetController extends Controller
{
    protected $sales_target;
    protected $rules = [
        'regional_sales_manager' => 'required|integer',
        'year' => 'required|date',
        'month' => 'required|string',
        'zone_id' => 'required|integer',
        'customer_industry' => 'required|string',
        'target' => 'required|integer'
    ];

    public function __construct(SalesTarget $sales_target)
    {
        parent::__construct($sales_target, $this->rules);
        $this->sales_target = $sales_target;
    }

    public function getUniqueYears()
    {
        return $this->sales_target->getUniqueYears();
    }

    public function getFilteredResults(Request $request)
    {
        $conditions = $request->all();

        return $this->sales_target->getFilteredResults($conditions);
    }

    public function sumByYears()
    {
        return $this->sales_target->sumByYears();
    }
}