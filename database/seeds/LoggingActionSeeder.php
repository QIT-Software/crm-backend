<?php

use Illuminate\Database\Seeder;
use App\LoggingAction;

class LoggingActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $actions = ['add', 'update', 'delete',];
        foreach ($actions as $action){
            LoggingAction::create([
                'action_name' => $action
            ]);
        }
    }
}
