<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class LinkBudgetAnalysisStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $list = [];
        $failure_reason = ['Satellite Coverage', 'Customer Equipment', 'Request Changed', 'Other Reason'];

        for ($i=1; $i < 50; $i++) {
            $list[] = [
                'opportunity_id' => $i,
                'allocated_bandwidth' => str_random(5),
                'data_rate' => str_random(5),
                'uplink_location' => str_random(5),
                'downlink_location' => str_random(5),
                'modem_model' => str_random(5),
                'satellite_name' => str_random(5),
                'recommended_hpa_size' => str_random(5),
                'recommended_hpa_size' => str_random(5),
                'title' => 'Link Title'.$i,
                'lost_reason' => $failure_reason[array_rand($failure_reason)],
                'created_at' => Carbon::now()->subYear()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ];
        }

        DB::table('link_budget_analysis_stage')->insert($list);
    }
}
