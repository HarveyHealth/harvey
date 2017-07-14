<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropPractitionerTypeColumnOfPractitionersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('practitioners', function (Blueprint $table) {
            $table->dropForeign(['practitioner_type']);
            $table->dropColumn('practitioner_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('practitioners', function (Blueprint $table) {
            $table->integer('practitioner_type')->nullable()->unsigned()->after('user_id');
            $table->foreign('practitioner_type')->references('id')->on('practitioner_types');
        });

    }
}
