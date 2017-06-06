<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id')->unsigned();
            $table->integer('practitioner_id')->unsigned();
            $table->tinyInteger('status_id')->unsigned()->default(0)->index();
            $table->string('shipment_code')->index();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->foreign('patient_id')->references('id')->on('patients');
            $table->foreign('practitioner_id')->references('id')->on('practitioners');
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
        Schema::dropIfExists('lab_orders');
    }
}
