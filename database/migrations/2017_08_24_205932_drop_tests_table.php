<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('tests');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id')->unsigned();
            $table->foreign('patient_id')->references('id')->on('patients');
            $table->integer('practitioner_id')->unsigned();
            $table->foreign('practitioner_id')->references('id')->on('practitioners');
            $table->integer('sku_id')->unsigned();
            $table->foreign('sku_id')->references('id')->on('skus');
            $table->string('results_key')->nullable();
            $table->timestamps();
        });
    }
}
