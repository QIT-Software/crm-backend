<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropRelatedToFromActivities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activity_calls', function (Blueprint $table) {
            $table->dropColumn(['related_to']);
        });
        Schema::table('activity_emails', function (Blueprint $table) {
            $table->dropColumn(['related_to']);
        });
        Schema::table('activity_events', function (Blueprint $table) {
            $table->dropColumn(['related_to']);
        });
        Schema::table('activity_tasks', function (Blueprint $table) {
            $table->dropColumn(['related_to']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
