<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_tests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lab_order_id')->unsigned();
            $table->integer('sku_id')->unsigned()->index();
            $table->tinyInteger('status_id')->unsigned()->default(0)->index();
            $table->string('results_url')->nullable();
            $table->string('shipment_code')->index();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->foreign('lab_order_id')->references('id')->on('lab_orders');
            $table->foreign('sku_id')->references('id')->on('skus');
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
        Schema::dropIfExists('lab_tests');
    }
}
