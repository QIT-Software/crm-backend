<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class SalesTarget extends Model
{
    use SoftDeletes;

    protected $table = 'sales_targets';
    private $targets_with_profit_view = 'targets_with_profit_view';


    protected $fillable = [
        'regional_sales_manager', 'year', 'month',
        'zone_id', 'customer_industry', 'target'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function getUniqueYears()
    {
        $years = DB::table($this->table)
            ->distinct('year')
            ->select('year')
            ->whereNull('deleted_at')
            ->get();

        return response()->json($years);
    }

    public function getFilteredResults($conditions)
    {
        $result = DB::table($this->targets_with_profit_view)
            ->join("{$this->erp}.PEOPLE', 'PEOPLE.ID", "{$this->targets_with_profit_view}.regional_sales_manager")
            ->join('zones', 'zones.id', "{$this->targets_with_profit_view}.zone_id")
            ->where(function ($query) use ($conditions) {
                $this->checkAndGetInNeededConditions($query, $conditions);
            })
            ->whereNull("{$this->targets_with_profit_view}.deleted_at")
            ->select(
                'PEOPLE.NAME as regional_sales_manager', 'zones.zone as region',
                "{$this->targets_with_profit_view}.year", "{$this->targets_with_profit_view}.month", "{$this->targets_with_profit_view}.id",
                "{$this->targets_with_profit_view}.customer_industry", "{$this->targets_with_profit_view}.target",
                "{$this->targets_with_profit_view}.profit", "{$this->targets_with_profit_view}.effectivity_percent"
            )
            ->get();

        return response()->json($result);
    }

    private function checkAndGetInNeededConditions($query, $conditions)
    {
        if (empty($conditions)) {
            return;
        }

        foreach ($conditions as $column => $neededToEqual) {
            $query->where("{$this->targets_with_profit_view}.{$column}", $neededToEqual);
        }
    }

    public function sumByYears()
    {
        $result = SalesTarget::groupBy(DB::raw('year(created_at)'))
            ->select(
                DB::raw('sum(target) as sum'),
                DB::raw('year(created_at) as year'),
                DB::raw('year(created_at) as id')
            )
            ->orderBy('year', 'asc')
            ->get();

        return response()->json($result);
    }
}
