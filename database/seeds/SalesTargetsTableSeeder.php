<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SalesTargetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $years = ['2018', '2019', '2020'];
        $customer_industry = ['Data', 'Video'];
        $list = [];
        $months = [
            'January', 'February', 'March', 'April',
            'May', 'June', 'July', 'August', 'September',
            'October', 'November', 'December'
        ];

        for($i = 1; $i < 50; $i++) {
            $list[] = [
                'regional_sales_manager' => rand(1, 6),
                'year' => $years[array_rand($years)],
                'month' => $months[array_rand($months)],
                'zone_id' => rand(1, 4),
                'customer_industry' => $customer_industry[array_rand($customer_industry)],
                'target' => rand(1000, 1000000),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ];
        }

        DB::table('sales_targets')->insert($list);
    }
}
