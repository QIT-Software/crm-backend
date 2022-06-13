<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DemoFormStageSeeder extends Seeder
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
                'requested_period' => str_random(5),
                'opportunity_id' => $i,
                'date_rate' => str_random(5),
                'status' => str_random(3),
                'start' => Carbon::now()->format('Y-m-d H:i:s'),
                'end' => Carbon::now()->format('Y-m-d H:i:s'),
                'title' => 'Awesome Title '.$i,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ];
        }

        DB::table('demo_form_stage')->insert($list);
    }
}
