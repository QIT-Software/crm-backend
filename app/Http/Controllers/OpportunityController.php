<?php

namespace App\Http\Controllers;

use App\Opportunity;
use App\Account;
use App\StagesHistory;
use Illuminate\Http\Request;
use DB;

class OpportunityController extends Controller
{
    protected $rules = [
        'account_id' => 'required|integer',
        'title' => 'required|string',
        'date' => 'sometimes|required|date',
        'satellite' => 'string',
        'type' => 'integer',
        'capacity' => 'string',
        'amount' => 'numeric',
        'band' => 'string',
        'capacity_type' => 'string',
        'current_stage_id' => 'required|integer',
        'zone_id' => 'nullable|integer',
        'manager_id' => 'sometimes|required|integer',
    ];
    protected $opportunity;

    public function __construct(Opportunity $opportunity)
    {
        parent::__construct($opportunity, $this->rules);
        $this->opportunity = $opportunity;
    }

    public function leadToQuote()
    {
        $ratio = $this->opportunity->leadToQuote();

        return response()->json($this->convertNullsToZero($ratio));
    }

    public function quoteToClose()
    {
        $ratio = $this->opportunity->quoteToClose();

        return response()->json($this->convertNullsToZero($ratio));
    }

    private function convertNullsToZero($ratio)
    {
        for ($index = 0; $index < count($ratio); $index++) {
            if (is_null($ratio[$index]->offers)) {
                $ratio[$index]->offers = 0;
            }
        }

        return $ratio;
    }

    public function totalCountChart(Request $request)
    {
        return $this->opportunity->totalCountChart($request->satellites);
    }

    public function fetch(Request $conditions, $id = null)
    {
        $module = 'opportunities';

        if ($conditions->method() !== 'POST') {
            $conditions = null;
        }

        return $this->opportunity->fetch($conditions, $id, $module);
    }

    public function dealsFetch(Request $conditions, $id = null)
    {
        $module = 'deals';

        if ($conditions->method() !== 'POST') {
            $conditions = null;
        }

        return $this->opportunity->fetch($conditions, $id, $module);
    }

    public function selectStages()
    {
        return $this->opportunity->selectStages();
    }

    public function getStagesData($id)
    {
        return $this->opportunity->getStagesData($id);
    }

    public function getOpportunitiesCountByStages(Request $request)
    {
        return $this->opportunity->getOpportunitiesCountByStages($request->satellites);
    }

    public function getTerminatedOpportunitiesCount(Request $request)
    {
        return $this->opportunity->getTerminatedOpportunitiesCount($request->satellites);
    }

    public function getDealsCountByStages(Request $request)
    {
        return $this->opportunity->getDealsCountByStages($request->satellites);
    }

    public function getTerminatedDealsCount(Request $request)
    {
        return $this->opportunity->getTerminatedDealsCount($request->satellites);
    }

    public function selectInvoice($id)
    {
        return $this->opportunity->selectInvoice($id);
    }

    public function selectServiceOrder($id)
    {
        return $this->opportunity->selectServiceOrder($id);
    }

    public function azspace1Capacity() {
        return $this->opportunity->azspace1Capacity();
    }

    public function azspace2Capacity() {
        return $this->opportunity->azspace2Capacity();
    }

    public function kuBandChart() {
        return $this->opportunity->kuBandChart();
    }

    public function cBandChart() {
        return $this->opportunity->cBandChart();
    }

    public function totalCount(Request $request)
    {
        return $this->opportunity->totalCount($request->satellites);
    }

    public function countByManagers(Request $request)
    {
        return $this->opportunity->countByManagers($request->satellites);
    }

    public function countBySegments(Request $request)
    {
        return $this->opportunity->countBySegments($request->satellites);
    }

    public function countByRegions(Request $request)
    {
        return $this->opportunity->countByRegions($request->satellites);
    }

    public function updateById(Request $request, $id) {
        if($request->has('changeStage') && $request->changeStage) {
            StagesHistory::create(['opportunity_id' => $id, 'stage_id' => $request->current_stage_id]);
        }
        $this->validate($request, $this->rules);
        $update = $this->opportunity::findOrFail($id);
        $status = $update->fill($request->all())->save();
        return response()->json($status);
    }

    public function getHistory($id) {
        $stages_history = new StagesHistory();
        $result = $stages_history->getHistory($id);
        return response()->json($result);
    }

    public function terminateById(Request $request) {
        $termination_reason = $request->input('termination_reason');
        $id = $request->input('id');
        return $this->opportunity->terminateById($termination_reason, $id);
    }
}
