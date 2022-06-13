<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinkBudgetAnalysisStage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('link_budget_analysis_stage', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('opportunity_id');
            $table->foreign('opportunity_id')->references('id')->on('opportunities');
            $table->string('allocated_bandwidth');
            $table->string('data_rate');
            $table->string('downlink_location');
            $table->string('uplink_location');
            $table->string('modem_model');
            $table->string('satellite_name');
            $table->string('recommended_hpa_size');
            $table->string('title');
            $table->string('lost_reason')->nullable();
            $table->text('fail_note')->nullable();
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
        Schema::dropIfExists('link_budget_analysis_stage');
    }
}
