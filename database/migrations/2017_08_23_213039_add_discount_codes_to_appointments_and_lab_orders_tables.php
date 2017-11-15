<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDiscountCodesToAppointmentsAndLabOrdersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->integer('discount_code_id')->unsigned()->nullable()->after('practitioner_id');
            $table->foreign('discount_code_id')->references('id')->on('discount_codes');
        });

        Schema::table('lab_orders', function (Blueprint $table) {
            $table->integer('discount_code_id')->unsigned()->nullable()->after('practitioner_id');
            $table->foreign('discount_code_id')->references('id')->on('discount_codes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('lab_orders', function (Blueprint $table) {
            $table->dropForeign(['discount_code_id']);
            $table->dropColumn('discount_code_id');
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['discount_code_id']);
            $table->dropColumn('discount_code_id');
        });
    }
}
