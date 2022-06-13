<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class NegotiationStageSeeder extends Seeder
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
                'status' => str_random(5),
                'opportunity_id' => $i,
                'declined_due_to' => str_random(5),
                'note' => str_random(3),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ];
        }

        DB::table('negotiation_stage')->insert($list);
    }
}
