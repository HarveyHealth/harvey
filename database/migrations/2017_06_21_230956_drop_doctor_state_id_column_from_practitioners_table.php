<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropDoctorStateIdColumnFromPractitionersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('practitioners', function (Blueprint $table) {
            $table->dropIndex(['doctor_state_id']);
        });

        Schema::table('practitioners', function (Blueprint $table) {
            $table->dropColumn('doctor_state_id');
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
            $table->tinyInteger('doctor_state_id')->after('practitioner_type')->default(0);
            $table->index('doctor_state_id');
        });
    }
}
