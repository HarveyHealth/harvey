<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVisibilityIdColumnToLabTestsInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lab_tests_information', function (Blueprint $table) {
            $table->tinyInteger('visibility_id')->after('quote')->default(0);
            $table->index('visibility_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lab_tests_information', function (Blueprint $table) {
            $table->dropIndex(['visibility_id']);
        });

        Schema::table('lab_tests_information', function (Blueprint $table) {
            $table->dropColumn('visibility_id');
        });
    }
}
