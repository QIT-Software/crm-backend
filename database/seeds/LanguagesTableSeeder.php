<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('languages')->insert([
            ['language' => 'Azerbaijani', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['language' => 'Russian', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['language' => 'English', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['language' => 'Turkish', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
