<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSpecialtyFieldsToPractitionersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('practitioners', function (Blueprint $table) {
            $table->string('specialty_1')->after('background_picture_url')->nullable();
            $table->string('specialty_2')->after('specialty_1')->nullable();
            $table->string('specialty_3')->after('specialty_2')->nullable();
            $table->string('specialty_4')->after('specialty_3')->nullable();
            $table->string('specialty_5')->after('specialty_4')->nullable();
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
            $table->dropColumn(['specialty_1', 'specialty_2', 'specialty_3', 'specialty_4', 'specialty_5']);
        });
    }
}
