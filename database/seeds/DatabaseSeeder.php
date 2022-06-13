<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            LanguagesTableSeeder::class,
            SoftwaresTableSeeder::class,
            ZonesTableSeeder::class,
            // AccountsTableSeeder::class,
            // ContactsTableSeeder::class,
            // ActivityCallsTableSeeder::class,
            // ActivityEmailsTableSeeder::class,
            // ActivityEventsTableSeeder::class,
            // ActivityTasksTableSeeder::class,
            // OpportunitiesTableSeeder::class,
            StagesNamesTableSeeder::class,
            // NotesTableSeeder::class,
            // CustomerRequestStageSeeder::class,
            // TechnicalFeasibilityStageSeeder::class,
            // LinkBudgetAnalysisStageSeeder::class,
            // NegotiationStageSeeder::class,
            // DemoFormStageSeeder::class,
            // ContractStageSeeder::class,
            // InvoiceSeeder::class,
            // ServiceOrderSeeder::class,
            // CommercialOfferStageSeeder::class,
            LoggingModuleSeeder::class,
            LoggingActionSeeder::class,
            SalesTargetsTableSeeder::class,
            TypesTableSeeder::class,
            // ZonesManagersTableSeeder::class,
            // AccountsManagersTableSeeder::class
        ]);
    }
}
