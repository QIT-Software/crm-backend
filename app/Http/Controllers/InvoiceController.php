<?php

namespace App\Http\Controllers;

use App\Invoice;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    protected $rules = [
        'opportunity_id' => 'integer',
        'price' => 'integer',
        'amount' => 'numeric',
        'due_date' => 'date',
        'status' => 'string',
        'title' => 'string',
        'date' => 'date'
    ];
    protected $invoice;
    protected $scheduler;

    public function __construct(Invoice $invoice)
    {
        parent::__construct($invoice, $this->rules);
        $this->invoice = $invoice;
    }

    public function fetchAll()
    {
        return $this->invoice->fetchAll();
    }

    public function selectByOpportunityId($id)
    {
        $result = Invoice::where('opportunity_id', $id)
            ->latest()
            ->get(['title']);

        return response()->json($result);
    }

    public function concludedInvoices()
    {
        return $this->invoice->concludedInvoices();
    }

    public function concludedInvoicesGroupedByZones()
    {
        return $this->invoice->concludedInvoicesGroupedByZones();
    }

    public function concludedInvoicesGroupedByManagers()
    {
        return $this->invoice->concludedInvoicesGroupedByManagers();
    }

    public function totalProfit(Request $request)
    {
        if($request->has('from') && $request->has('to')) {
            return $this->invoice->totalProfit($request->from, $request->to, $request->satellites);
        }

        return $this->invoice->totalProfit(Carbon::today()->toDateString(), Carbon::tomorrow()->toDateString(), $request->satellites);
    }

    public function totalProfitChart(Request $request)
    {
        if($request->has('from') && $request->has('to')) {
            return $this->invoice->totalProfitChart($request->from, $request->to, $request->satellites);
        }

        return $this->invoice->totalProfitChart(Carbon::today()->toDateString(), Carbon::tomorrow()->toDateString(), $request->satellites);
    }

    public function profitByRegions(Request $request)
    {
        if($request->has('from') && $request->has('to')) {
            return $this->invoice->profitByRegions($request->from, $request->to, $request->satellites);
        }

        return $this->invoice->profitByRegions(Carbon::today()->toDateString(), Carbon::tomorrow()->toDateString(), $request->satellites);
    }

    public function profitByManagers(Request $request)
    {
        if($request->has('from') && $request->has('to')) {
            return $this->invoice->profitByManagers($request->from, $request->to, $request->satellites);
        }

        return $this->invoice->profitByManagers(Carbon::today()->toDateString(), Carbon::tomorrow()->toDateString(), $request->satellites);
    }

    public function profitBySegments(Request $request)
    {
        if($request->has('from') && $request->has('to')) {
            return $this->invoice->profitBySegments($request->from, $request->to, $request->satellites);
        }

        return $this->invoice->profitBySegments(Carbon::today()->toDateString(), Carbon::tomorrow()->toDateString(), $request->satellites);
    }
}
