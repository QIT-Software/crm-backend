<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SoftwaresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('softwares')->insert([
            ['software' => 'SAP', 'language_id' => '3', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['software' => '1C', 'language_id' => '1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        ]);
    }
}
