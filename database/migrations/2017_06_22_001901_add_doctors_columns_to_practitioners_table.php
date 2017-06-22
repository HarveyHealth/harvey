<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDoctorsColumnsToPractitionersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('practitioners', function (Blueprint $table) {
            $table->dateTime('graduated_at')->after('user_id')->nullable();
            $table->json('specialty')->after('user_id')->nullable();
            $table->string('background_picture_url')->after('user_id')->nullable();
            $table->string('picture_url')->after('user_id')->nullable();
            $table->string('school')->after('user_id')->nullable();
            $table->text('description')->after('user_id')->nullable();
            $table->integer('license_id')->after('user_id')->unsigned()->nullable();
            $table->foreign('license_id')->references('id')->on('licenses');
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
            $table->dropForeign(['license_id']);
            $table->dropColumn(
                'graduated_at',
                'specialty',
                'background_picture_url',
                'picture_url',
                'school',
                'description',
                'license_id'
            );
        });
    }
}
