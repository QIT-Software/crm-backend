<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTechnicalFeasibilityStage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('technical_feasibility_stage', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('opportunity_id');
            $table->foreign('opportunity_id')->references('id')->on('opportunities');
            $table->string('location');
            $table->string('site_latitude');
            $table->string('site_longitude');
            $table->string('antenna_diameter');
            $table->string('antenna_polarization');
            $table->string('hpa_size');
            $table->string('sat_equipment');
            $table->string('inbound_data_rate');
            $table->string('outbound_data_rate');
            $table->text('note');
            $table->string('title');
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
        Schema::dropIfExists('technical_feasibility_stage');
    }
}
