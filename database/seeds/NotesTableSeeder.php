<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class NotesTableSeeder extends Seeder
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
                'note' => str_random(5),
                'account_id' => $i,
                'created_by' => $i,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        DB::table('notes')->insert($list);
    }
}
