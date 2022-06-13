<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Schema;
use DB;
use Carbon\Carbon;
use App\Traits\ArrayOperations;

use Laravel\Lumen\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use ArrayOperations;

    private $model;
    private $rules;

    public function __construct($model, $rules)
    {
        $this->model = $model;
        $this->rules = $rules;
    }

    public function selectFields()
    {
        $columns = Schema::getColumnListing($this->model->getTable());
        $keys = array_diff($columns, ['updated_at', 'deleted_at']);
        $fields = [];
        $fieldsKey = [];
        foreach ($keys as $field) {
            $fieldsKey[] = strtr($field, ['_id' => '']);
            $fields[] = ucwords(strtr($field, ['_' => ' ',
                '_id' => '']));
        }
        return response()->json(array_combine($fieldsKey, $fields));
    }

    // public function lostCountByReasons() {
    //     $byWeek = $this->groupingReasonsByPeriods('week', Carbon::now()->subWeek());
    //     $byMonth = $this->groupingReasonsByPeriods('month', Carbon::now()->subMonth());
    //     $byYear = $this->groupingReasonsByPeriods('year', Carbon::now()->subYear());
    //     $byQuarter = $this->groupingReasonsByPeriods('quarter', Carbon::now()->subQuarter());
    //     $total = $this->groupingReasonsByPeriods('total', 0);

    //     $result = $this->getMergedArray($total, $byWeek, $byMonth, $byQuarter, $byYear);

    //     return response()->json($result);
    // }

    // private function groupingReasonsByPeriods($period, $subPeriod) {
    //     return DB::table($this->model->getTable())
    //             ->groupBy("lost_reason")
    //             ->select(
    //                 DB::raw("ifnull(lost_reason, 'Won') as lost_reason"),
    //                 DB::raw("count(*) as {$period}"),
    //                 DB::raw("ifnull(lost_reason, 0) as id")
    //             )
    //             ->where("created_at", ">", $subPeriod)
    //             ->whereNull("deleted_at")
    //             ->orderBy('lost_reason', 'asc')
    //             ->get();
    // }

    // public function countByManagers() {
    //     $byWeek = $this->groupingManagersByPeriods('week', Carbon::now()->subWeek());
    //     $byMonth = $this->groupingManagersByPeriods('month', Carbon::now()->subMonth());
    //     $byYear = $this->groupingManagersByPeriods('year', Carbon::now()->subYear());
    //     $byQuarter = $this->groupingManagersByPeriods('quarter', Carbon::now()->subQuarter());
    //     $total = $this->groupingManagersByPeriods('total', 0);

    //     $result = $this->getMergedArray($total, $byWeek, $byMonth, $byQuarter, $byYear);

    //     return response()->json($result);
    // }

    // private function groupingManagersByPeriods($period, $subPeriod) {
    //     return DB::table($this->model->getTable())
    //              ->groupBy("accounts_managers.manager_id", "zones.id")
    //              ->join("opportunities", "opportunities.id", $this->model->getTable().".opportunity_id")
    //              ->join("accounts", "accounts.id", "opportunities.account_id")
    //              ->join("accounts_managers", "accounts_managers.account_id", "accounts.id")
    //              ->join("zones", "zones.id", "accounts.zone_id")
    //              ->join("erp.PEOPLE", "PEOPLE.ID", "accounts_managers.manager_id")
    //              ->select("PEOPLE.NAME as manager",
    //                       "zones.zone",
    //                       DB::raw("count(*) as {$period}"),
    //                       DB::raw("(accounts_managers.manager_id + zones.id) as id"))
    //              ->where($this->model->getTable().".created_at", ">", $subPeriod)
    //              ->whereNull($this->model->getTable().".deleted_at")
    //              ->whereNull("accounts_managers.deleted_at")
    //              ->whereNull("opportunities.deleted_at")
    //              ->whereNull("accounts.deleted_at")
    //              ->get();
    // }

    public function add(Request $request)
    {
        $this->validate($request, $this->rules);
        $lastInserted = $this->model::create($request->all());
        $id = $lastInserted->id;

        if ($this->model instanceof \App\Contracts\Mailable) {
            // event($this->model->mail($id));
        }

        return $id;
    }

    public function updateById(Request $request, $id)
    {
        $this->validate($request, $this->rules);
        $update = $this->model::findOrFail($id);
        $result = $update->fill($request->all())->save();
        return response()->json($result);
    }

    public function deleteById($id)
    {
        $delete = $this->model::findOrFail($id);
        $result = $delete->delete();
        return response()->json($result);
    }
}
