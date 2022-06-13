<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Attachment extends Model
{
    use SoftDeletes;

    protected $table = 'attachments';
    
    protected $fillable = [
        'name'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $dates = [
        'deleted_at'
    ];
    
    public function selectByAccountId($id)
    {
        $results = DB::table($this->table)
            ->join('attachments_accounts as pivot', $this->table.'.id', 'pivot.attachment_id')
            ->select($this->table.'.name', $this->table.'.id')
            ->where('pivot.account_id', $id)
            ->whereNull($this->table.'.deleted_at')
            ->get();

        return response()->json($results);
    }

    public function selectReportByAccountId($id)
    {
        $results = DB::table($this->table)
            ->join("reports_accounts as pivot", "{$this->table}.id", "pivot.attachment_id")
            ->select("{$this->table}.name", "{$this->table}.id")
            ->where("pivot.account_id", $id)
            ->whereNull("{$this->table}.deleted_at")
            ->get();

        return response()->json($results);
    }

    public function selectByTechnicalFeasibilityId($id)
    {
        $results = DB::table($this->table)
            ->join('attachments_tf_reports as pivot', $this->table.'.id', 'pivot.attachment_id')
            ->select($this->table.'.name', $this->table.'.id')
            ->where('pivot.technical_feasibility_id', $id)
            ->whereNull($this->table.'.deleted_at')
            ->get();

        return response()->json($results);
    }

    public function selectLBAAttachByOpportunityId($id)
    {
        $results = DB::table($this->table)
            ->join('attachments_lba_reports as pivot', $this->table.'.id', 'pivot.attachment_id')
            ->join('link_budget_analysis_stage as stage', 'stage.id', 'pivot.link_budget_id')
            ->select($this->table.'.name', $this->table.'.id', 'pivot.link_budget_id')
            ->where('stage.opportunity_id', $id)
            ->whereNull($this->table.'.deleted_at')
            ->whereNull('stage.deleted_at')
            ->get();

        return response()->json($results);
    }

    public function selectByDemoFormId($id)
    {
        $results = DB::table($this->table)
            ->join('attachments_demo_forms as pivot', $this->table.'.id', 'pivot.attachment_id')
            ->select($this->table.'.name', $this->table.'.id')
            ->where('pivot.demo_form_id', $id)
            ->whereNull($this->table.'.deleted_at')
            ->get();

        return response()->json($results);
    }

    public function selectByContractId($id)
    {
        $results = DB::table($this->table)
            ->join('attachments_contracts as pivot', $this->table.'.id', 'pivot.attachment_id')
            ->select($this->table.'.name', $this->table.'.id')
            ->where('pivot.contract_id', $id)
            ->whereNull($this->table.'.deleted_at')
            ->get();

        return response()->json($results);
    }

    public function selectByServiceOrderId($id)
    {
        $results = DB::table($this->table)
            ->join('attachments_service_orders as pivot', $this->table.'.id', 'pivot.attachment_id')
            ->select($this->table.'.name', $this->table.'.id')
            ->where('pivot.service_order_id', $id)
            ->whereNull($this->table.'.deleted_at')
            ->get();

        return response()->json($results);
    }

    public function selectByInvoiceId($id)
    {
        $results = DB::table($this->table)
            ->join('attachments_invoices as pivot', $this->table.'.id', 'pivot.attachment_id')
            ->select($this->table.'.name', $this->table.'.id')
            ->where('pivot.invoice_id', $id)
            ->whereNull($this->table.'.deleted_at')
            ->get();

        return response()->json($results);
    }
}
