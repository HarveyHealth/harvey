<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyColumnsOfPrescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropForeign(['sku_id']);
            $table->dropColumn('sku_id');
        });

        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropForeign(['practitioner_id']);
            $table->dropColumn('practitioner_id');
        });

        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropColumn('accepted_at');
        });

        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropColumn('rejected_at');
        });

        Schema::table('prescriptions', function (Blueprint $table) {
            $table->integer('created_by_user_id')->unsigned()->after('patient_id')->nullable();
            $table->foreign('created_by_user_id')->references('id')->on('users');
            $table->string('key')->nullable();
            $table->string('notes')->nullable();
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
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->integer('sku_id')->unsigned()->nullable();
            $table->foreign('sku_id')->references('id')->on('skus');
            $table->datetime('accepted_at')->nullable();
            $table->datetime('rejected_at')->nullable();
            $table->integer('practitioner_id')->unsigned()->nullable();
            $table->foreign('practitioner_id')->references('id')->on('practitioners');
        });

        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropForeign(['created_by_user_id']);
            $table->dropColumn('created_by_user_id');
        });

        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropColumn('notes');
        });

        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropColumn('key');
        });

        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

    }
}
