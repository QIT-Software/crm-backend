<?php

use Illuminate\Database\Seeder;

class AccountsManagersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1; $i < 50; $i++) {
            $list[] = [
                'account_id' => $i,
                'manager_id' => rand(1, 154),
            ];
        }

        DB::table('accounts_managers')->insert($list);
    }
}
