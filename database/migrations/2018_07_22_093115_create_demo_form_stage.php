<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDemoFormStage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demo_form_stage', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('opportunity_id');
            $table->foreign('opportunity_id')->references('id')->on('opportunities');
            $table->string('title');
            $table->string('requested_period');
            $table->string('date_rate');
            $table->date('start');
            $table->date('end');
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demo_form_stage');
    }
}
