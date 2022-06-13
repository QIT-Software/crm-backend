<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateSalesManagersProfitsView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE OR REPLACE VIEW sales_managers_profits_view AS
    SELECT
        joined_users_accounts_opportunities_invoices_view.ID,
        joined_users_accounts_opportunities_invoices_view.NAME,
        SUM(joined_users_accounts_opportunities_invoices_view.price) AS 'profit',
        joined_users_accounts_opportunities_invoices_view.year,
        joined_users_accounts_opportunities_invoices_view.month
    FROM
        joined_users_accounts_opportunities_invoices_view
    GROUP BY
		joined_users_accounts_opportunities_invoices_view.year ,
        joined_users_accounts_opportunities_invoices_view.month ,
        joined_users_accounts_opportunities_invoices_view.ID");
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
