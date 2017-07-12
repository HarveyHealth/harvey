<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLicenseColumnsToPractitionersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('practitioners', function (Blueprint $table) {
            $table->string('license_state', 20)->after('user_id')->nullable();
            $table->integer('license_number')->after('user_id')->unsigned()->nullable();
            $table->string('license_title', 3)->after('user_id')->nullable();
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
            $table->dropColumn([
                'license_title',
                'license_number',
                'license_state',
            ]);
        });
    }
}
