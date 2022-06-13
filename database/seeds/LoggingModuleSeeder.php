<?php

use Illuminate\Database\Seeder;
use App\LoggingModule;

class LoggingModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules = ['accounts', 'contacts', 'opportunities', 'tasks', 'events', 'calls', 'emails', 'notes', 'negotiation', 'invoice', 'service_order', 'demo_form', 'commercial_offer', 'customer_request', 'technical_feasibility_report', 'link_budget_analysis_report', 'attachments',];
        foreach ($modules as $module){
            LoggingModule::create([
                'module_name' => $module
            ]);
        }
    }
}
