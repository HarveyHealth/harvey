<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabTestsLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_tests_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lab_test_id')->unsigned()->index();
            $table->string('attribute')->index();
            $table->string('from');
            $table->string('to');
            $table->foreign('lab_test_id')->references('id')->on('lab_tests');
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
        Schema::dropIfExists('lab_tests_log');
    }
}
