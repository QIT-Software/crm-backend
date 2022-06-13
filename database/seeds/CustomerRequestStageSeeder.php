<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CustomerRequestStageSeeder extends Seeder
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
                'service_region' => str_random(5),
                'frequency_band' => str_random(5),
                'mbit_mhz' => str_random(3),
                'details' => str_random(10),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ];
        }

        DB::table('customer_request_stage')->insert($list);
    }
}
