<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoapNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('soap_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->foreign('patient_id')->references('id')->on('patients');
            $table->integer('patient_id')->unsigned();
            $table->integer('created_by_user_id')->unsigned();
            $table->foreign('created_by_user_id')->references('id')->on('users');
            $table->string('notes')->nullable();
            $table->string('subjective')->nullable();
            $table->string('objective')->nullable();
            $table->string('assessment')->nullable();
            $table->string('plan')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('soap_notes');
    }
}
