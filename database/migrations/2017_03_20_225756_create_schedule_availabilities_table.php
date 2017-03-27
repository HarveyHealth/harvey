<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduleAvailabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('practitioner_availabilities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('practitioner_id')->unsigned();
            $table->foreign('practitioner_id')->references('id')->on('practitioners');
            $table->string('day_of_week', 12);
            $table->time('start_time');
            $table->time('stop_time');
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
        Schema::table('practitioner_availabilities', function (Blueprint $table) {
            $table->dropForeign(['practitioner_id']);
            $table->drop();
        });
    }
}
