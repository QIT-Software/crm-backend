<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('types')->insert([
            ['type' => 'Data', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['type' => 'Video', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        ]);
    }
}
