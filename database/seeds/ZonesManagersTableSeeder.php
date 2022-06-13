<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ZonesManagersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('zones_managers')->insert([
            ['zone_id' => '1', 'manager_id' => '34', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['zone_id' => '1', 'manager_id' => '68', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['zone_id' => '2', 'manager_id' => '23', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['zone_id' => '3', 'manager_id' => '39', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['zone_id' => '4', 'manager_id' => '145', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['zone_id' => '5', 'manager_id' => '147', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        ]);
    }
}
