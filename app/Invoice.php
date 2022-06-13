<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Contracts\Mailable;
use App\Contracts\Notifiable;
use App\Contracts\Watchable;
use Carbon\Carbon;
use DB;

class Invoice extends Model implements Mailable, Notifiable, Watchable
{
    use SoftDeletes;

    protected $table = 'invoice';

    protected $fillable = [
        'opportunity_id', 'amount',
        'price', 'due_date', 'status', 'title', 'date'
    ];

    protected $hidden = [
        'updated_at', 'deleted_at'
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function fetchAll()
    {
        $result = DB::table($this->table)
            ->join('opportunities', "{$this->table}.opportunity_id", 'opportunities.id')
            ->join('accounts', 'opportunities.account_id', 'accounts.id')
            ->select(
                'opportunities.title as opportunity_title', "{$this->table}.title as invoice_title",
                'accounts.company_name', 'price', "{$this->table}.amount", 'due_date', "{$this->table}.date",
                'status'
            )
            ->get();

        return response()->json($result);
    }

    public function concludedInvoicesGroupedByZones()
    {
        $result = DB::table($this->table)
            ->groupBy("zones.zone")
            ->join("opportunities", "opportunities.id", $this->table . ".opportunity_id")
            ->join("accounts", "accounts.id", "opportunities.account_id")
            ->join("zones", "zones.id", "accounts.zone_id")
            ->whereNull($this->table . ".deleted_at")
            ->select("zones.zone", "accounts.customer_industry", DB::raw("count(*) as count"), "zones.id as id")
            ->get();

        return response()->json($result);
    }

    public function concludedInvoicesGroupedByManagers()
    {
        $result = DB::table($this->table)
            ->groupBy("regional_sales_manager")
            ->join("opportunities", "opportunities.id", $this->table . ".opportunity_id")
            ->join("accounts", "accounts.id", "opportunities.account_id")
            ->join("{$this->erp}.PEOPLE", "PEOPLE.ID", "accounts.regional_sales_manager")
            ->whereNull($this->table . ".deleted_at")
            ->select(
                "PEOPLE.LOGIN as manager", "accounts.customer_industry",
                DB::raw("count(*) as count"), "PEOPLE.ID as id")
            ->get();

        return response()->json($result);
    }

    public function concludedInvoices()
    {
        $result = DB::table($this->table)
            ->groupBy("customer_industry")
            ->join("opportunities", "opportunities.id", $this->table . ".opportunity_id")
            ->join("accounts", "accounts.id", "opportunities.account_id")
            ->whereNull($this->table . ".deleted_at")
            ->select("customer_industry", DB::raw("count(*) as count"), "customer_industry as id")
            ->get();

        return response()->json($result);
    }

    public function totalProfit($from, $to, $satellites)
    {
        $result = DB::table($this->table)
            ->leftJoin('opportunities', 'opportunities.id', "{$this->table}.opportunity_id")
            ->leftJoin("{$this->cm}.contract_infos", 'opportunities.id', "{$this->cm}.contract_infos.opportunity_id")
            ->leftJoin("{$this->cm}.contracts", "{$this->cm}.contracts.id", "{$this->cm}.contract_infos.contract_id")
            ->leftJoin("{$this->cm}.currencies", "{$this->cm}.contracts.currency_id", "{$this->cm}.currencies.id")
            ->whereBetween('invoice.date', [$from, $to])
            ->whereNull("{$this->cm}.contracts.deleted_at")

            ->whereNull("invoice.deleted_at")
            ->whereIn('opportunities.satellite', $satellites)
            ->sum('contracts.price');

        return response()->json($result);
    }

    public function totalProfitChart($from = '', $to = '', $satellites)
    {
        if ($from === '') $from = Carbon::today()->toDateString();
        if ($to === '') $to = Carbon::tomorrow()->toDateString();

        $result = DB::table($this->table)
            ->leftJoin("{$this->cm}.contract_infos", 'opportunities.id', "{$this->cm}.contract_infos.opportunity_id")
            ->leftJoin("{$this->cm}.contracts", "{$this->cm}.contracts.id", "{$this->cm}.contract_infos.contract_id")
            ->leftJoin("{$this->cm}.currencies", "{$this->cm}.contracts.currency_id", "{$this->cm}.currencies.id")
            ->leftJoin('opportunities', 'opportunities.id', "{$this->table}.opportunity_id")
            ->whereBetween('invoice.date', [$from, $to])

            ->whereNull("{$this->cm}.contracts.deleted_at")
            ->whereIn('opportunities.satellite', $satellites)
            ->groupBy(DB::raw("DATE_FORMAT(invoice.date, '%Y')"))
            ->whereNull("invoice.deleted_at")
            ->select(
                DB::raw("DATE_FORMAT(invoice.date, '%Y') as year"),
                DB::raw("sum(contracts.price) as price")
            )
            ->get();

        return response()->json($result);
    }

    public function profitByRegions($from = '', $to = '', $satellites)
    {
        if ($from === '') $from = Carbon::today()->toDateString();
        if ($to === '') $to = Carbon::tomorrow()->toDateString();

        $currencies = DB::table("{$this->cm}.currencies")
            ->get();
        foreach ($currencies as $currency) {
            $result = DB::table('zones')
                ->leftJoin('opportunities AS opps', function ($join) use ($satellites) {
                    $join->on('zones.id', '=', 'opps.zone_id')
                        ->whereIn('opps.satellite', $satellites)
                        ->whereNull('opps.deleted_at');
                })
                ->leftJoin('invoice', function ($join) use ($from, $to) {
                    $join->on('opps.id', '=', 'invoice.opportunity_id')
                        ->whereBetween('invoice.date', [$from, $to])
                        ->whereNull('invoice.deleted_at');
                })
                ->leftJoin("{$this->cm}.contract_infos", 'opps.id', "{$this->cm}.contract_infos.opportunity_id")
                ->leftJoin("{$this->cm}.contracts", "{$this->cm}.contracts.id", "{$this->cm}.contract_infos.contract_id")
                ->leftJoin("{$this->cm}.currencies", "{$this->cm}.contracts.currency_id", "{$this->cm}.currencies.id")
                ->whereNull("zones.deleted_at")

                ->whereNull("{$this->cm}.contracts.deleted_at")
                ->where("{$this->cm}.currencies.id", $currency->id)
                ->groupBy('zones.id')
                ->select(
                    "zones.zone as region",
                    DB::raw("IFNULL(SUM(invoice.amount), 0) as data"),
                    DB::raw("sum(
                        case when
                            opps.current_stage_id >= 7 && opps.termination_reason IS NULL
                        then ifnull(contracts.price, 0) else 0 end
                    ) as deals
                "), DB::raw("sum(
                        case when
                            opps.current_stage_id < 7 && opps.termination_reason IS NULL
                        then ifnull(contracts.price, 0) else 0 end
                    ) as ongoing
                ")
                )
                ->get();
            $currency->profit = $result;
        }
        $data["regions"] = DB::table('zones')->get();
        $data["currencies"] = $currencies;

        return response()->json($data);
    }

    public function profitByManagers($from = '', $to = '', $satellites)
    {
        if ($from === '') $from = Carbon::today()->toDateString();
        if ($to === '') $to = Carbon::tomorrow()->toDateString();
        $currencies = DB::table("{$this->cm}.currencies")
            ->get();
        foreach ($currencies as $currency) {

            $result = DB::table("{$this->erp}.PEOPLE as people")
                ->leftJoin('opportunities AS opps', function ($join) use ($satellites) {
                    $join->on('people.ID', '=', 'opps.manager_id')
                        ->whereIn('opps.satellite', $satellites)
                        ->whereNull('opps.deleted_at');
                })
                ->leftJoin("{$this->cm}.contract_infos", 'opps.id', "{$this->cm}.contract_infos.opportunity_id")
                ->leftJoin("{$this->cm}.contracts", "{$this->cm}.contracts.id", "{$this->cm}.contract_infos.contract_id")
                ->leftJoin("{$this->cm}.currencies", "{$this->cm}.contracts.currency_id", "{$this->cm}.currencies.id")

                ->whereNull("{$this->cm}.contracts.deleted_at")
                ->where('people.DELETED', '=', 0)
                ->where('people.GROUP', '=', 12)
                ->where('people.PRIVILEGES', 'like', '%E_CRM%')
                ->where("{$this->cm}.currencies.id", $currency->id)
                ->groupBy('people.ID')
                ->select(
                    "people.NAME as manager",
                    DB::raw("IFNULL(SUM(contracts.price), 0) as data"),
                    DB::raw("sum(
                        case when
                            opps.current_stage_id >= 7 && opps.termination_reason IS NULL
                        then ifnull(contracts.price, 0) else 0 end
                    ) as deals
                "), DB::raw("sum(
                        case when
                            opps.current_stage_id < 7 && opps.termination_reason IS NULL
                        then ifnull(contracts.price, 0) else 0 end
                    ) as ongoing
                ")
                )
                ->get();
            $currency->profit = $result;


        }
        $data["managers"] = DB::table("{$this->erp}.PEOPLE as people")
            ->leftJoin('opportunities AS opps', function ($join) use ($satellites) {
                $join->on('people.ID', '=', 'opps.manager_id')
                    ->whereIn('opps.satellite', $satellites)
                    ->whereNull('opps.deleted_at');
            })
            ->where('people.DELETED', '=', 0)
            ->where('people.GROUP', '=', 12)
            ->where('people.PRIVILEGES', 'like', '%E_CRM%')
            ->groupBy('people.ID')
            ->select(
                "people.NAME as manager"
            )
            ->get();
        $data["currencies"] = $currencies;

        return response()->json($data);
    }

    public function profitBySegments($from = '', $to = '', $satellites)
    {
        if ($from === '') $from = Carbon::today()->toDateString();
        if ($to === '') $to = Carbon::tomorrow()->toDateString();
        $currencies = DB::table("{$this->cm}.currencies")
            ->get();
        foreach ($currencies as $currency) {

            $result = DB::table('opportunities AS opps')
                ->join('types', 'types.id', 'opps.type')
                ->leftJoin("{$this->cm}.contract_infos", 'opps.id', "{$this->cm}.contract_infos.opportunity_id")
                ->leftJoin("{$this->cm}.contracts", "{$this->cm}.contracts.id", "{$this->cm}.contract_infos.contract_id")
                ->leftJoin("{$this->cm}.currencies", "{$this->cm}.contracts.currency_id", "{$this->cm}.currencies.id")

                ->whereNull("{$this->cm}.contracts.deleted_at")
                ->whereNull("opps.deleted_at")
                ->whereIn('opps.satellite', $satellites)
                ->where("{$this->cm}.currencies.id", $currency->id)
                ->groupBy('opps.type')
                ->select(
                    "types.type as segment",
                    DB::raw("IFNULL(SUM(contracts.price), 0) as data"),
                    DB::raw("sum(
                        case when
                            opps.current_stage_id >= 7 && opps.termination_reason IS NULL
                        then ifnull(contracts.price, 0) else 0 end
                    ) as deals
                "), DB::raw("sum(
                        case when
                            opps.current_stage_id < 7 && opps.termination_reason IS NULL
                        then ifnull(contracts.price, 0) else 0 end
                    ) as ongoing
                ")
                )
                ->get();
            $currency->profit = $result;

        }
        $data["segments"] = DB::table('types')->select("types.type as segment")->get();
        $data["currencies"] = $currencies;

        return response()->json($data);
    }

    public function mail($id)
    {
        return new \App\Events\MailSenderEvent(
            Invoice::find($id)
        );
    }

    public function subject()
    {
        return 'New invoice was added.';
    }

    public function informSubject()
    {
        return 'The Invoice due date has come.';
    }

    public function body()
    {
        $body = "Opportunity: <strong>{$this->opportunity->title}</strong>\n";
        $body .= "Company Name: <strong>{$this->relatedCompany()}</strong>\n\n";
        $body .= "Parameters:\n";
        $body .= "\t1. Title: <strong>{$this->title}</strong>\n";
        $body .= "\t2. Price: <strong>{$this->price}</strong>\n";
        $body .= "\t3. Amount: <strong>{$this->amount}</strong>\n";
        $body .= "\t4. Due Date: <strong>{$this->due_date}</strong>\n";
        $body .= "\t6. Status: <strong>{$this->status}</strong>";

        return $body;
    }

    // Adapter
    public function informBody()
    {
        return $this->body();
    }

    public function opportunity()
    {
        return $this->belongsTo('App\Opportunity');
    }

    public function message()
    {
        $msg = "<strong>{$this->opportunity->title}</strong> opportunity's invoice date,";
        $msg .= " related to <strong>{$this->relatedCompany()}</strong>,";
        $msg .= " titled as <strong>{$this->title}</strong> has come.";

        return $msg;
    }

    public function owners()
    {
        return $this->opportunity->account->managers;
    }
}
