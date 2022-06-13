<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachmentsTechnicalFeasibilityReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments_tf_reports', function (Blueprint $table) {
            $table->unsignedInteger('technical_feasibility_id');
            $table->foreign('technical_feasibility_id')->references('id')->on('technical_feasibility_stage');
            $table->unsignedInteger('attachment_id');
            $table->foreign('attachment_id')->references('id')->on('attachments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attachments_technical_feasibility_reports');
    }
}
