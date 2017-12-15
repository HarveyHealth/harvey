<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNotesToPractitionerSchedulesTable extends Migration
{
    public function up()
    {
        Schema::table('practitioner_schedules', function (Blueprint $table) {
            $table->string('notes', 191)->nullable()->after('stop_time');
        });
    }

    public function down()
    {
        Schema::table('practitioner_schedules', function (Blueprint $table) {
            $table->dropColumn('notes');
        });
    }
}
