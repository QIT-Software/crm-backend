<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ZonesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('zones')->insert([
            ['zone' => 'Europe', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['zone' => 'MENA', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['zone' => 'Africa', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['zone' => 'CIS', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['zone' => 'Azerbaijan', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
