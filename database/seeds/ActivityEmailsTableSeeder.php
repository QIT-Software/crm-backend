<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ActivityEmailsTableSeeder extends Seeder
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
                'from' => str_random(10),
                'to' => str_random(10),
                'bcc' => str_random(5),
                'account_id' => $i,
                'subject' => str_random(10),
                'content' => str_random(10),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ];
        }

        DB::table('activity_emails')->insert($list);
    }
}
