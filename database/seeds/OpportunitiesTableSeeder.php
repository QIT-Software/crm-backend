<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OpportunitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $list = [];

        for($i = 1; $i < 50; $i++) {
            $list[] = [
                'title' => str_random(5),
                'account_id' => $i,
                'type' => str_random(5),
                // 'progress' => str_random(5),
                'capacity' => random_int(1, 100),
                'current_stage_id' => random_int(1, 9),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        DB::table('opportunities')->insert($list);
    }
}
