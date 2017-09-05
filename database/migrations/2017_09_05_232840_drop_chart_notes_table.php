<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropChartNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('chart_notes');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('chart_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id')->unsigned();
            $table->foreign('patient_id')->references('id')->on('patients');
            $table->integer('practitioner_id')->unsigned();
            $table->foreign('practitioner_id')->references('id')->on('practitioners');
            $table->integer('appointment_id')->unsigned()->nullable();
            $table->foreign('appointment_id')->references('id')->on('appointments');
            $table->string('note');
            $table->timestamps();
        });
    }
}
