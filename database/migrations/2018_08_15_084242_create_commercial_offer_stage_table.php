<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommercialOfferStageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commercial_offer_stage', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('opportunity_id');
            $table->foreign('opportunity_id')->references('id')->on('opportunities');
            $table->date('start');
            $table->date('end');
            $table->text('description');
            $table->string('band');
            $table->string('segment');
            $table->string('type_of_service');
            $table->integer('volume');
            $table->string('unit');
            $table->string('period_of_lease');
            $table->date('service_start');
            $table->date('service_end');
            $table->string('notice_period');
            $table->string('free_trial_time');
            $table->string('other_conditions');
            $table->string('payment_condition');
            $table->text('further_notice');
            $table->string('price');
            $table->string('availability');
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
        Schema::dropIfExists('commercial_offer_stage');
    }
}
