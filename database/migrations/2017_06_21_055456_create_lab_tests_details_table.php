<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabTestsDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_tests_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sku_id')->unsigned();
            $table->mediumText('description');
            $table->string('image')
            $table->string('lab_name');
            $table->string('name')
            $table->string('sample');
            $table->text('quote');
            $table->foreign('sku_id')->references('id')->on('skus');
            $table->softDeletes();
        });

        $this->seedTable();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lab_tests_details');
    }
}

