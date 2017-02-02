<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChartNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chart_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_user_id')->unsigned();
            $table->foreign('patient_user_id')->references('id')->on('users');
            $table->integer('practitioner_user_id')->unsigned();
            $table->foreign('practitioner_user_id')->references('id')->on('users');
            $table->integer('appointment_id')->unsigned()->nullable();
            $table->foreign('appointment_id')->references('id')->on('users');
            $table->string('note');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chart_notes');
    }
}
