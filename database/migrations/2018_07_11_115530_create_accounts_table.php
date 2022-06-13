<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_name');
            $table->string('short_name');
            $table->string('email');
            $table->string('country');
            $table->string('city');
            $table->string('address_line_1');
            $table->string('address_line_2');
            $table->string('postal_code');
            $table->string('province');
            $table->string('phone');
            $table->string('web');
            $table->unsignedInteger('language_id');
            $table->foreign('language_id')->references('id')->on('languages');
            $table->unsignedInteger('software_id');
            $table->foreign('software_id')->references('id')->on('softwares');
            $table->string('customer_industry');
            $table->string('customer_type');
            $table->string('customer_status');
            $table->unsignedInteger('zone_id');
            $table->foreign('zone_id')->references('id')->on('zones');
            $table->date('created_date');
            $table->string('account_manager');
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
        Schema::dropIfExists('accounts');
    }
}
