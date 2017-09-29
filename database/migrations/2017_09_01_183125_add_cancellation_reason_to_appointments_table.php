<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCancellationReasonToAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
         Schema::table('appointments', function (Blueprint $table) {
             $table->string('cancellation_reason')->after('google_calendar_event_id')->nullable();
         });
     }

     /**
      * Reverse the migrations.
      *
      * @return void
      */
     public function down()
     {
         Schema::table('appointments', function (Blueprint $table) {
             $table->dropColumn('cancellation_reason');
         });
     }
}
