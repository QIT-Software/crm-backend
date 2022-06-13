<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateTargetsWithProfitView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE OR REPLACE VIEW targets_with_profit_view AS
    SELECT
        sales_targets.*,
        IFNULL(sales_managers_profits_view.profit, 0) AS 'profit',
        IFNULL(ROUND((sales_managers_profits_view.profit * 100) / sales_targets.target, 2), 0) AS 'effectivity_percent'
    FROM
        sales_targets
            LEFT JOIN
        sales_managers_profits_view ON sales_targets.regional_sales_manager = sales_managers_profits_view.ID
            AND sales_targets.year = sales_managers_profits_view.year
            AND sales_targets.month = sales_managers_profits_view.month
            ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
