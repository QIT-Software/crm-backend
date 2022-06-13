<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_targets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('regional_sales_manager');
            $table->year('year');
            $table->string('month');
            $table->unsignedInteger('zone_id');
            $table->foreign('zone_id')->references('id')->on('zones');
            $table->string('customer_industry');
            $table->unsignedInteger('target');
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
        Schema::dropIfExists('sales_targets');
    }
}
