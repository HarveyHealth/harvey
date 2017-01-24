<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientSymptomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_symptoms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_user_id')->unsigned();
            $table->integer('symptom_id')->unsigned();
            $table->foreign('patient_user_id')->references('id')->on('users');
            $table->foreign('symptom_id')->references('id')->on('symptoms');
            $table->smallInteger('severity');
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
        Schema::dropIfExists('patient_symptoms');
    }
}
