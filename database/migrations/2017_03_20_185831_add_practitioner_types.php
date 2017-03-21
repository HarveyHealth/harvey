<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPractitionerTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('practitioners', function (Blueprint $table) {
            $table->integer('practitioner_type')->unsigned()->after('user_id')->default(1);
            $table->foreign('practitioner_type')->references('id')->on('practitioner_types');
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
            $table->dropForeign(['practitioner_type']);
//            $table->dropColumn('practitioner_type');
        });
    }
}
