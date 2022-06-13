<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateJoinedUsersAccountsOpportunitiesInvoicesView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $erp = env('DB2_DATABASE', 'erp');
        $crm = env('DB_DATABASE', 'crm');

        $query = "CREATE OR REPLACE VIEW joined_users_accounts_opportunities_invoices_view AS
                SELECT
                    ".$erp.".PEOPLE.ID,
                    ".$erp.".PEOPLE.NAME,
                    ".$crm.".invoice.price,
                    ".$crm.".invoice.due_date,
                    DATE_FORMAT(".$crm.".invoice.due_date, '%Y') AS 'year',
                    DATE_FORMAT(".$crm.".invoice.due_date, '%M') AS 'month'
                FROM
                    ".$erp.".PEOPLE
                        INNER JOIN
                    ".$crm.".accounts_managers ON ".$erp.".PEOPLE.ID = ".$crm.".accounts_managers.manager_id
                        INNER JOIN
                    ".$crm.".accounts ON ".$crm.".accounts_managers.account_id = ".$crm.".accounts.id
                        INNER JOIN
                    ".$crm.".opportunities ON ".$crm.".accounts.id = ".$crm.".opportunities.account_id
                        INNER JOIN
                    ".$crm.".invoice ON ".$crm.".opportunities.id = ".$crm.".invoice.opportunity_id";
        DB::statement($query);
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
