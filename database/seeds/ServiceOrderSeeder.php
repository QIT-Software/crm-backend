<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ServiceOrderSeeder extends Seeder
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
                'opportunity_id' => $i,
                'reference_number' => random_int(1, 10),
                'date_of_issue' => Carbon::now()->format('Y-m-d'),
                'date_of_service' => Carbon::now()->format('Y-m-d'),
                'date_of_amendments' => Carbon::now()->format('Y-m-d'),
                'amount' => random_int(1, 100),
                'title' => 'Awesome Title '.$i,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        DB::table('service_order')->insert($list);
    }
}
