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
            $table->integer('patient_user_id')->unsigned();
            $table->foreign('patient_user_id')->references('id')->on('users');
            $table->integer('practitioner_user_id')->unsigned();
            $table->foreign('practitioner_user_id')->references('id')->on('users');
            $table->datetime('disabled_at')->nullable()->index();
            $table->integer('disabled_by')->unsigned()->nullable();
            $table->foreign('disabled_by')->references('id')->on('users');
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
