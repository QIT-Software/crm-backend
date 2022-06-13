<?php

use Illuminate\Database\Seeder;

class StagesNamesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('stages_names')->insert([
            ['stage_id' => '1', 'stage_name' => 'Customer Request', 'progress' => '15%'],
            ['stage_id' => '2', 'stage_name' => 'Technical Feasibility', 'progress' => '30%'],
            ['stage_id' => '3', 'stage_name' => 'Link Budget Analysis Report', 'progress' => '45%'],
            ['stage_id' => '4', 'stage_name' => 'Commercial Offer', 'progress' => '60%'],
            ['stage_id' => '5', 'stage_name' => 'Negotiation', 'progress' => '75%'],
            ['stage_id' => '6', 'stage_name' => 'Demo Form/Free Usage', 'progress' => '85%'],
            ['stage_id' => '7', 'stage_name' => 'Contract', 'progress' => '100%'],
            ['stage_id' => '9', 'stage_name' => 'Invoice', 'progress' => '100%']
        ]);
    }
}
