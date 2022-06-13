<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ContractStageSeeder extends Seeder
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
                'opportunity_id' => $i,
                'reference_number' => rand(),
                'date_of_conclusion' => Carbon::now()->format('Y-m-d H:i:s'),
                'date_of_expiry' => Carbon::now()->format('Y-m-d H:i:s'),
                'date_of_ammendments' => Carbon::now()->format('Y-m-d H:i:s'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ];
        }

        DB::table('contract_stage')->insert($list);
    }
}
