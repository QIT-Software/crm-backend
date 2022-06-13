<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ActivityTasksTableSeeder extends Seeder
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
                'due_date' => Carbon::now()->format('Y-m-d H:i:s'),
                'contact_id' => $i,
                'account_id' => $i,
                'assigned_to' => rand(1, 6),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ];
        }

        DB::table('activity_tasks')->insert($list);
    }
}
