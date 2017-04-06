<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_practitioner', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id')->unsigned();
            $table->foreign('patient_id')->references('id')->on('patients');
            $table->integer('practitioner_id')->unsigned();
            $table->foreign('practitioner_id')->references('id')->on('practitioners');
            $table->datetime('disabled_at')->nullable()->index();
            $table->integer('disabled_by')->unsigned()->nullable();
            $table->foreign('disabled_by')->references('id')->on('admins');
            $table->text('disabled_reason')->nullable();
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
        Schema::dropIfExists('patient_practitioner');
    }
}
