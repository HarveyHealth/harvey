<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePractitionerScheduleOverridesTable extends Migration
{
    public function up()
    {
        Schema::create('practitioner_schedule_overrides', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('practitioner_id')->unsigned();
            $table->foreign('practitioner_id')->references('id')->on('practitioners');
            $table->date('date');
            $table->index('date');
            $table->time('start_time');
            $table->time('stop_time');
            $table->string('notes', 191)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('practitioner_schedule_overrides', function (Blueprint $table) {
            $table->dropIndex(['date']);
            $table->dropForeign(['practitioner_id']);
        });

        Schema::dropIfExists('practitioner_schedule_overrides');
    }
}
