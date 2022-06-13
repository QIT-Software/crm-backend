<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TechnicalFeasibilityStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $list = [];

        for ($i=1; $i < 50; $i++) {
            $list[] = [
                'opportunity_id' => $i,
                'location' => str_random(5),
                'site_latitude' => str_random(5),
                'site_longitude' => str_random(5),
                'antenna_diameter' => str_random(5),
                'antenna_polarization' => str_random(5),
                'hpa_size' => str_random(5),
                'sat_equipment' => str_random(5),
                'inbound_data_rate' => str_random(5),
                'outbound_data_rate' => str_random(5),
                'note' => str_random(15),
                'title' => 'Tech Title'.$i,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ];
        }

        DB::table('technical_feasibility_stage')->insert($list);
    }
}
