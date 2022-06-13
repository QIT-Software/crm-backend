<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $industries = ['Data', 'Video'];
        // $statuses = ['Lead', 'Active', 'Passive'];
        $list = [];

        for($i = 0; $i < 50; $i++) {
            $list[] = [
                'company_name' => 'Company ' . $i,
                'email' => 'Email' . $i . '@test.com',
                'short_name' => 'Short Name ' . $i,
                'country' => 'Country ' . $i,
                'city' => 'City ' . $i,
                'address_line_1' => 'Address Line 1-' . $i,
                'address_line_2' => 'Address Line 2-' . $i,
                'postal_code' => 'Postal Code ' . $i,
                'province' => 'Province ' . $i,
                'phone' => 'Phone ' . $i,
                'web' => 'Web ' . $i,
                'language_id' => rand(1, 4),
                'software_id' => rand(1, 2),
                'customer_industry' => $industries[array_rand($industries)],
                'customer_type' => 'Customer Type ' . $i,
                // 'customer_status' => $statuses[array_rand($statuses)],
                // 'zone_id' => rand(1, 4),
                'created_date' => Carbon::now(),
                'account_manager' => "Javidan Feyziyev",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        DB::table('accounts')->insert($list);

    }
}
