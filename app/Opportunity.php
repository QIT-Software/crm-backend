<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class Opportunity extends Model
{
    use SoftDeletes;

    protected $table = 'opportunities';

    protected $fillable = [
        'account_id', 'title', 'type',
        'satellite',
// 'amount',
//        'capacity', 'capacity_type', 'band',
        'current_stage_id', 'zone_id', 'manager_id', 'date'
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function fetch($conditions, $id, $module)
    {
        $query = DB::table($this->table)
            ->join('accounts', 'accounts.id', 'opportunities.account_id')
            ->join('stages_names', 'stages_names.stage_id', 'opportunities.current_stage_id')
            ->join('types', 'types.id', 'opportunities.type')
            ->leftJoin('zones', 'zones.id', 'opportunities.zone_id')
            ->leftJoin("{$this->erp}.PEOPLE", 'PEOPLE.ID', 'opportunities.manager_id')
            ->leftJoin("{$this->cm}.contract_infos", 'opportunities.id', "{$this->cm}.contract_infos.opportunity_id")
            ->leftJoin("{$this->cm}.contracts", "{$this->cm}.contracts.id", "{$this->cm}.contract_infos.contract_id")
            ->leftJoin("{$this->cm}.currencies", "{$this->cm}.contracts.currency_id", "{$this->cm}.currencies.id")
            ->whereNull('opportunities.deleted_at')
            ->whereNull("{$this->cm}.contracts.deleted_at")
            ->whereNull('stages_names.deleted_at')
            ->whereNull('contracts.sub_contract_id')
            ->whereNull('accounts.deleted_at')
            ->where(function ($query) use ($id, $conditions) {
                $this->checkIdIsEmpty($query, $id);
                $this->compare($conditions, $query);
            })
            ->orderBy('id', 'desc')
            ->select(
                'opportunities.id', 'opportunities.account_id', 'PEOPLE.NAME as manager', 'PEOPLE.ID as manager_id',
                'accounts.company_name as account', 'types.type', 'opportunities.type as type_id', 'title', 'satellite',
                'stages_names.progress as progress', 'opportunities.current_stage_id',
                'stages_names.stage_name as current_stage',
                'opportunities.created_at as create_date',
//              'capacity',  'capacity_type','opportunities.amount'
                'zones.id as zone_id', 'zones.zone', 'opportunities.termination_reason', 'opportunities.date',
                DB::raw("CASE WHEN opportunities.termination_reason IS NOT NULL
                                    THEN 'Terminated'
                                ELSE 'Ongoing' END as status"),
                "{$this->cm}.contract_infos.transponder_band as band",
                "{$this->cm}.contract_infos.transponder_value as capacity",
                "{$this->cm}.contract_infos.transponder_measurement as capacity_type",
                "{$this->cm}.contracts.price as amount",
                "{$this->cm}.currencies.name as currency"
            );

        if ($module === 'opportunities' && $id == null) {
            $query->where('opportunities.current_stage_id', '<', 7);
        } else if ($module === 'deals') {
            $query->where('opportunities.current_stage_id', '>=', 7);
        }

        $result = $query->get();

        return response()->json($result);
    }

    public function selectStages()
    {
        $result = DB::table('stages_names')
            ->whereNull('stages_names.deleted_at')
            ->get(['stage_id as id', 'stage_name as stage']);

        return response()->json($result);
    }

    public function getStagesData($id)
    {
        $contract = ContractStage::where('opportunity_id', $id)->latest()->get();
        $customer_request = CustomerRequestStage::where('opportunity_id', $id)->latest()->get();
        $demo_form = DemoFormStage::where('opportunity_id', $id)->latest()->get();
        $link_budget_analysis = LinkBudgetAnalysisStage::where('opportunity_id', $id)->latest()->get();
        $negotiation = NegotiationStage::where('opportunity_id', $id)->latest()->get();
        $technical_feasibility = TechnicalFeasibilityStage::where('opportunity_id', $id)->latest()->get();
        $commercial_offer = CommercialOfferStage::where('opportunity_id', $id)->latest()->get();
        // $service_order = ServiceOrder::where('opportunity_id', $id)->latest()->get();
        $invoice = Invoice::where('opportunity_id', $id)->latest()->get();

        return response()->json([
            'contract' => $contract,
            'customer_request' => $customer_request,
            'demo_form_free_usage' => $demo_form,
            'link_budget_analysis' => $link_budget_analysis,
            'commercial_offer' => $commercial_offer,
            'negotiation' => $negotiation,
            'technical_feasibility' => $technical_feasibility,
            // 'service_order' => $service_order,
            'invoice' => $invoice
        ]);
    }

    public function azspace1Capacity()
    {
        $mhz = DB::table($this->table)
            ->join('stages_names', 'stages_names.stage_id', 'opportunities.current_stage_id')
            ->leftJoin("{$this->cm}.contract_infos", 'opportunities.id', "{$this->cm}.contract_infos.opportunity_id")
            ->leftJoin("{$this->cm}.contracts", "{$this->cm}.contracts.id", "{$this->cm}.contract_infos.contract_id")
            ->leftJoin("{$this->cm}.currencies", "{$this->cm}.contracts.currency_id", "{$this->cm}.currencies.id")
            ->whereNull('stages_names.deleted_at')
            ->whereNull("{$this->cm}.contracts.deleted_at")
            ->whereNull('opportunities.deleted_at')
            ->whereNull('termination_reason')
            ->whereIn('contract_infos.transponder_band', ['C band', 'Ku band'])
            ->where('contract_infos.transponder_measurement', '=', 'Mhz')
            ->where('current_stage_id', '>=', 7)
            ->groupBy('band')
            ->select(
                DB::raw("IFNULL(ROUND(sum(contract_infos.transponder_value), 2), 0 ) as capacity"),
                "{$this->cm}.contract_infos.transponder_band as band",
                "{$this->cm}.contract_infos.transponder_measurement as capacity_type"
            )
            ->get();

        $mbps = DB::table($this->table)
            ->leftJoin("{$this->cm}.contract_infos", 'opportunities.id', "{$this->cm}.contract_infos.opportunity_id")
            ->leftJoin("{$this->cm}.contracts", "{$this->cm}.contracts.id", "{$this->cm}.contract_infos.contract_id")
            ->leftJoin("{$this->cm}.currencies", "{$this->cm}.contracts.currency_id", "{$this->cm}.currencies.id")
            ->groupBy('band')
            ->whereNull("{$this->cm}.contracts.deleted_at")
            ->whereNull('opportunities.deleted_at')
            ->whereNull('termination_reason')
            ->where('contract_infos.transponder_band', '=', 'Ku band')
            ->where('contract_infos.transponder_measurement', '=', 'Mbps')
            ->where('current_stage_id', '>=', 7)
            ->select(
                DB::raw("IFNULL(ROUND(sum(contract_infos.transponder_value), 2), 0 ) as capacity"),
                "{$this->cm}.contract_infos.transponder_band as band",
                "{$this->cm}.contract_infos.transponder_measurement as capacity_type"
            )
            ->get();

        return response()->json([
            'mhz' => $mhz,
            'mbps' => $mbps
        ]);

        return response()->json($result);
    }

    public function azspace2Capacity()
    {
        $result = DB::table($this->table)
            ->leftJoin("{$this->cm}.contract_infos", 'opportunities.id', "{$this->cm}.contract_infos.opportunity_id")
            ->leftJoin("{$this->cm}.contracts", "{$this->cm}.contracts.id", "{$this->cm}.contract_infos.contract_id")
            ->leftJoin("{$this->cm}.currencies", "{$this->cm}.contracts.currency_id", "{$this->cm}.currencies.id")
            ->join('stages_names', 'stages_names.stage_id', 'opportunities.current_stage_id')
            ->whereNull('stages_names.deleted_at')
            ->whereNull("{$this->cm}.contracts.deleted_at")
            ->whereNull('opportunities.deleted_at')
            ->whereNull('termination_reason')
            ->where('current_stage_id', '>=', 7)
//            ->select(DB::raw("IFNULL(ROUND(sum(capacity), 2), 0 ) as capacity"))
            ->select(
                DB::raw("IFNULL(ROUND(sum(contract_infos.transponder_value), 2), 0 ) as capacity")
            )
            ->first();

        return response()->json($result);
    }

    public function cBandChart()
    {
        $average = $this->bandForChart('c', 'Mhz.');

        $video = $this->bandForChart('c', 'Mhz.', 'video');

        $data = $this->bandForChart('c', 'Mhz.', 'data');

        return response()->json([
            'average' => $average,
            'video' => $video,
            'data' => $data
        ]);
    }

    public function kuBandChart()
    {
        $mhz = $this->bandForChart('Ku', 'Mhz.');

        $mbps = $this->bandForChart('Ku', 'Mbps.');

        $local = $this->bandForChart('Ku', 'Mhz.', null, 'local');

        $local_video = $this->bandForChart('Ku', 'Mhz.', 'video', 'local');

        $local_data = $this->bandForChart('Ku', 'Mhz.', 'data', 'local');

        $overseas = $this->bandForChart('Ku', 'Mhz.', null, 'overseas');

        $overseas_video = $this->bandForChart('Ku', 'Mhz.', 'video', 'overseas');

        $overseas_data = $this->bandForChart('Ku', 'Mhz.', 'data', 'overseas');

        return response()->json([
            'mhz' => $mhz,
            'mbps' => $mbps,
            'local' => $local,
            'local_video' => $local_video,
            'local_data' => $local_data,
            'overseas' => $overseas,
            'overseas_video' => $overseas_video,
            'overseas_data' => $overseas_data
        ]);
    }

    private function bandForChart($band, $capacity_type, $type = null, $location = null)
    {

        $band = ucfirst($band) . " band";
        $capacity_type = str_replace(".", "", $capacity_type);
        $query = DB::table($this->table)
            ->leftJoin("{$this->cm}.contract_infos", 'opportunities.id', "{$this->cm}.contract_infos.opportunity_id")
            ->leftJoin("{$this->cm}.contracts", "{$this->cm}.contracts.id", "{$this->cm}.contract_infos.contract_id")
            ->leftJoin("{$this->cm}.currencies", "{$this->cm}.contracts.currency_id", "{$this->cm}.currencies.id")
            ->join('stages_names', 'stages_names.stage_id', 'opportunities.current_stage_id')
            ->whereNull('stages_names.deleted_at')
            ->whereNull("{$this->cm}.contracts.deleted_at")
            ->whereNull('opportunities.deleted_at')
            ->whereNull('termination_reason');
        if ($type) {
            $type = DB::table("types")->where('type', $type)->first();
            $query->where('type', '=', $type->id);
        }

        if ($band) {
            $query->where("{$this->cm}.contract_infos.transponder_band", '=', $band);
        }

        if ($capacity_type) {
            $query->where("{$this->cm}.contract_infos.transponder_measurement", '=', $capacity_type);
        }

        if ($location && $location == 'local') {
            $query->where('zone_id', '=', 5);
        } else if ($location == 'overseas') {
            $query->where('zone_id', '!=', 5);
        }

        $query
            ->where('current_stage_id', '>=', 7)
            ->where('satellite', '=', 'Azerspace-1')
            ->groupBy('band')
            ->select(DB::raw("IFNULL(ROUND(sum(contracts.price) / sum(contract_infos.transponder_value), 2), 0 ) as avarage"));

        return $query->get();
    }


    public function countByManagers($satellites)
    {
        $result = DB::table("{$this->erp}.PEOPLE as people")
            ->groupBy("people.ID")
            ->leftJoin("{$this->table}", function ($query) use ($satellites) {
                $query->on("people.ID", "{$this->table}.manager_id")
                    ->whereIn('satellite', $satellites);
            })
            ->where('people.DELETED', '=', 0)
            ->where('people.GROUP', '=', 12)
            ->whereNull('opportunities.deleted_at')
            ->where('people.PRIVILEGES', 'like', '%E_CRM%')
            ->select(
                "people.NAME as manager", "people.ID as id",
                DB::raw("sum(
                        case when
                            {$this->table}.current_stage_id >= 7 && {$this->table}.termination_reason IS NULL
                        then 1 else 0 end
                    ) as deals
                "), DB::raw("sum(
                        case when
                            {$this->table}.current_stage_id < 7 && {$this->table}.termination_reason IS NULL
                        then 1 else 0 end
                    ) as ongoing
                ")
            )
            ->get();

        return response()->json($result);
    }

    public function countBySegments($satellites)
    {
        $result = DB::table($this->table)
            ->join('types', 'types.id', 'opportunities.type')
            ->groupBy('opportunities.type')
            ->whereNull("{$this->table}.deleted_at")
            ->whereIn('satellite', $satellites)
            ->select("types.type as segment",
                DB::raw("sum(
                    case when
                        {$this->table}.current_stage_id >= 7 && {$this->table}.termination_reason IS NULL
                    then 1 else 0 end
                ) as deals
            "), DB::raw("sum(
                    case when
                        {$this->table}.current_stage_id < 7 && {$this->table}.termination_reason IS NULL
                    then 1 else 0 end
                ) as ongoing
            "))
            ->get();

        return response()->json($result);
    }

    public function countByRegions($satellites)
    {
        $result = DB::table('zones')
            ->groupBy("zones.id")
            ->leftJoin("{$this->table}", function ($query) use ($satellites) {
                $query->on('zones.id', "{$this->table}.zone_id")
                    ->whereIn('satellite', $satellites);
            })
            ->whereNull("{$this->table}.deleted_at")
            ->select('zones.zone as region',
                DB::raw("sum(
                    case when
                        opportunities.current_stage_id >= 7 && opportunities.termination_reason IS NULL
                    then 1 else 0 end
                ) as deals
            "), DB::raw("sum(
                    case when
                        opportunities.current_stage_id < 7 && opportunities.termination_reason IS NULL
                    then 1 else 0 end
                ) as ongoing
            "))
            ->get();
        // CASE WHEN opportunities.termination_reason IS NOT NULL
        //     THEN 'Terminated'
        // WHEN opportunities.current_stage_id >= 7
        //     THEN 'Deals'
        // ELSE 'Ongoing' END as status

        return response()->json($result);
    }

    // public function countByManagers()
    // {
    //     $byWeek = $this->managersByPeriod('week', Carbon::now()->subWeek());
    //     $byMonth = $this->managersByPeriod('month', Carbon::now()->subMonth());
    //     $byYear = $this->managersByPeriod('year', Carbon::now()->subYear());
    //     $byQuarter = $this->managersByPeriod('quarter', Carbon::now()->subQuarter());
    //     $total = $this->managersByPeriod('total', 0);

    //     $result = $this->getMergedArray($total, $byWeek, $byMonth, $byQuarter, $byYear);

    //     return response()->json($result);
    // }

    // private function managersByPeriod($period, $subPeriod)
    // {
    //     return DB::table($this->table)
    //             ->groupBy("accounts_managers.manager_id", "zones.id")
    //             ->join("accounts", "accounts.id", $this->table.".account_id")
    //             ->join("accounts_managers", "accounts_managers.account_id", "accounts.id")
    //             ->join("erp.PEOPLE", "PEOPLE.ID", "accounts_managers.manager_id")
    //             ->join("zones", "accounts.zone_id", "zones.id")
    //             ->select("PEOPLE.NAME as manager",
    //                      "zones.zone",
    //                      DB::raw("count(*) as {$period}"),
    //                      DB::raw("(accounts_managers.manager_id + zones.id) as id"))
    //             ->where($this->table.".created_at", ">", $subPeriod)
    //             ->whereNull($this->table.".deleted_at")
    //             ->whereNull("accounts_managers.deleted_at")
    //             ->whereNull("accounts.deleted_at")
    //             ->get();
    // }

    public function leadToQuote()
    {
        $ratio = $this->leadsAndOffersCount();

        return $ratio;
    }

    private function leadsAndOffersCount()
    {
        return DB::table('accounts')
            ->groupBy(DB::raw('year(created_at)'))
            ->where('customer_status', 'Lead')
            ->select(
                DB::raw('count(*) as leads'),
                DB::raw('year(created_at) as year'),
                DB::raw('(select count(*)
                         from commercial_offer_stage as offer
                         join opportunities on opportunities.id = offer.opportunity_id
                         join accounts on accounts.id = opportunities.account_id
                         where accounts.customer_status = "Lead"
                            and year(offer.created_at) = year
                         group by year(offer.created_at)) as offers')
            )
            ->orderBy('year', 'asc')
            ->get();
    }

    public function quoteToClose()
    {
        $ratio = $this->offersAndOrdersCount();

        return $ratio;
    }

    private function offersAndOrdersCount()
    {
        return DB::table('commercial_offer_stage')
            ->groupBy(DB::raw('year(created_at)'))
            ->select(
                DB::raw('count(*) as offers'),
                DB::raw('year(created_at) as year'),
                DB::raw('(select count(*)
                         from service_order
                         where year(created_at) = year
                         group by year(created_at)) as orders')
            )
            ->orderBy('year', 'asc')
            ->get();
    }

    public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function terminateById($termination_reason, $id)
    {
        return DB::table($this->table)
            ->where('id', $id)
            ->update(['termination_reason' => $termination_reason]);
    }

    public function getOpportunitiesCountByStages($satellites)
    {

        $result = DB::table('stages_names')
            ->leftJoin('opportunities AS opps', function ($join) use ($satellites) {
                $join->on('stages_names.stage_id', '=', 'opps.current_stage_id')
                    ->whereIn('opps.satellite', $satellites)
                    ->whereNull('opps.termination_reason')
                    ->whereNull('opps.deleted_at');
            })
            ->where('stages_names.id', '<', 7)
            ->whereNull('stages_names.deleted_at')
            ->groupBy('stages_names.id')
            ->orderBy('stages_names.stage_id')
            ->select('stages_names.stage_name as stage', 'stages_names.id', DB::raw('count(opps.id) as count'))
            ->get();

        return response()->json($result);
    }

    public function getTerminatedOpportunitiesCount($satellites)
    {

        $result = DB::table('opportunities AS opps')
            ->where('opps.current_stage_id', '<', 7)
            ->whereIn('opps.satellite', $satellites)
            ->whereNull('opps.deleted_at')
            ->whereNotNull('opps.termination_reason')
            ->count();

        return response()->json($result);
    }

    public function getDealsCountByStages($satellites)
    {

        $result = DB::table('stages_names')
            ->leftJoin('opportunities AS opps', function ($join) use ($satellites) {
                $join->on('stages_names.stage_id', '=', 'opps.current_stage_id')
                    ->whereIn('opps.satellite', $satellites)
                    ->whereNull('opps.termination_reason')
                    ->whereNull('opps.deleted_at');
            })
            ->where('stages_names.id', '>=', 7)
            ->whereNull('stages_names.deleted_at')
            ->groupBy('stages_names.id')
            ->orderBy('stages_names.stage_id')
            ->select('stages_names.stage_name as stage', 'stages_names.id', DB::raw('count(opps.id) as count'))
            ->get();

        return response()->json($result);
    }

    public function getTerminatedDealsCount($satellites)
    {

        $result = DB::table('opportunities AS opps')
            ->where('opps.current_stage_id', '>=', 7)
            ->whereIn('opps.satellite', $satellites)
            ->whereNull('opps.deleted_at')
            ->whereNotNull('opps.termination_reason')
            ->count();

        return response()->json($result);
    }

    public function totalCount($satellites)
    {
        $opps_count = Opportunity::whereIn('satellite', $satellites)
            ->where('current_stage_id', '<', 7)
            ->count();
        $deals_count = Opportunity::whereIn('satellite', $satellites)
            ->where('current_stage_id', '>=', 7)
            ->count();
        return response()->json([
            'opps_count' => $opps_count,
            'deals_count' => $deals_count
        ]);
    }

    public function totalCountChart($satellites)
    {
        $opps = DB::table($this->table)
            ->whereNull("deleted_at")
            ->where('current_stage_id', '<', 7)
            ->whereIn('satellite', $satellites)
            ->groupBy(DB::raw("DATE_FORMAT(date, '%Y')"))
            ->select(DB::raw("DATE_FORMAT(date, '%Y') as year"), DB::raw("count({$this->table}.id) as count"))
            ->get();

        $deals = DB::table($this->table)
            ->whereNull("deleted_at")
            ->whereIn('satellite', $satellites)
            ->where('current_stage_id', '>=', 7)
            ->groupBy(DB::raw("DATE_FORMAT(date, '%Y')"))
            ->select(DB::raw("DATE_FORMAT(date, '%Y') as year"), DB::raw("count({$this->table}.id) as count"))
            ->get();

        return response()->json([
            'opps' => $opps,
            'deals' => $deals
        ]);
    }
}
