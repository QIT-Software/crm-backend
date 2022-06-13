<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CommercialOfferStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $list = [];
        $bands = ['C', 'Ku'];
        $segments = ['Data', 'Video'];
        $service_types = ['Fixed', 'OUS', 'Pre-emptible', 'Other'];
        $units = ['MHz', 'Mbps'];
        $lease_periods = ['minute', 'hours', 'days', 'weeks', 'months', 'years'];
        $notice_periods = ['day', 'week', 'month'];
        $trial_periods = ['none', 'day', 'week', 'month'];
        $lost_reasons = ['Other conditions', 'Payment Condition', 'Availability', 'Price', null];

        for($i = 1; $i < 50; $i++) {
            $list[] = [
                'opportunity_id' => $i,
                'start' => Carbon::now(),
                'end' => Carbon::now(),
                'description' => str_random(10),
                'band' => $bands[array_rand($bands)],
                'segment' => $segments[array_rand($segments)],
                'type_of_service' => $service_types[array_rand($service_types)],
                'volume' => rand(1, 50),
                'unit' => $units[array_rand($units)],
                'period_of_lease' => $lease_periods[array_rand($lease_periods)],
                'service_start' => Carbon::now(),
                'service_end' => Carbon::now(),
                'notice_period' => $notice_periods[array_rand($notice_periods)],
                'free_trial_time' => $trial_periods[array_rand($trial_periods)],
                'other_conditions' => str_random(5),
                'payment_condition' => str_random(5),
                'further_notice' => str_random(10),
                'title' => 'Awesome title '.$i,
                'price' => str_random(3),
                'availability' => str_random(3),
                'lost_reason' => $lost_reasons[array_rand($lost_reasons)],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        DB::table('commercial_offer_stage')->insert($list);
    }
}
