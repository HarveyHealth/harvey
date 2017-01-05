<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_user_id')->unsigned();
            $table->foreign('patient_user_id')->references('id')->on('users');
            $table->integer('practitioner_user_id')->unsigned();
            $table->foreign('practitioner_user_id')->references('id')->on('users');
            $table->integer('sku_id')->unsigned();
            $table->foreign('sku_id')->references('id')->on('skus');
            $table->datetime('accepted_at')->nullable();
            $table->datetime('rejected_at')->nullable();
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
        Schema::dropIfExists('prescriptions');
    }
}
