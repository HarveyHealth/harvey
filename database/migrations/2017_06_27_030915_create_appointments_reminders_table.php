<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentsRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments_reminders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('appointment_id')->index()->unsigned();
            $table->foreign('appointment_id')->references('id')->on('appointments');
            $table->integer('recipient_user_id')->index()->unsigned();
            $table->foreign('recipient_user_id')->references('id')->on('users');
            $table->tinyInteger('type_id')->index()->default(0);
            $table->datetime('sent_at')->index();
            $table->timestamps();
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
        Schema::dropIfExists('appointments_reminders');
    }
}
