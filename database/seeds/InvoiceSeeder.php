<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class InvoiceSeeder extends Seeder
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
                'opportunity_id' => $i,
                'price' => rand(1, 100),
                'amount' => rand(1, 80),
                'date' => Carbon::now()->format('Y-m-d'),
                'due_date' => Carbon::now()->format('Y-m-d'),
                'status' => str_random(5),
                'title' => 'Awesome Title '.$i,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        DB::table('invoice')->insert($list);
    }
}
