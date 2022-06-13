<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ContactsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $list = [];

        for ($i=1; $i < 51; $i++) {
            $list[] = [
                'account_id' => $i,
                'name' => str_random(10),
                'surname' => str_random(10),
                'position' => str_random(10),
                'decision_maker' => true,
                'phone' => str_random(10),
                'email' => str_random(10).'@gmail.com',
                'can_directly_communicate' => true,
                'language_id' => rand(1, 4),
                'description' => str_random(50),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ];
        }

        DB::table('contacts')->insert($list);
    }
}
