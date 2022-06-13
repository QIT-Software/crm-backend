<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ActivityCallsTableSeeder extends Seeder
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
                'subject' => str_random(10),
                'comments' => str_random(10),
                'contact_id' => $i,
                'account_id' => $i,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ];
        }

        DB::table('activity_calls')->insert($list);
    }
}
