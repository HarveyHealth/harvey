<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\{License, Practitioner};

class DropLicenseIdColumnOfPractitionersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Practitioner::all()->each(function ($item, $key) {
//            $license = License::find($item->license_id);
//            $license->user_id = $item->user_id;
//            $license->save();
//        });

        Schema::table('practitioners', function (Blueprint $table) {
            $table->dropForeign(['license_id']);
            $table->dropColumn('license_id');
        });

//        if (License::all()->every(function ($value, $key) { return !empty($value->user_id); })) {
//            Schema::table('licenses', function (Blueprint $table) {
//                $table->integer('user_id')->unsigned()->nullable(false)->change();
//            });
//        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('practitioners', function (Blueprint $table) {
            $table->integer('license_id')->after('user_id')->unsigned()->nullable();
            $table->foreign('license_id')->references('id')->on('licenses');
        });

        Practitioner::all()->each(function ($item, $key) {
            $item->license_id = License::where('user_id', $item->user_id)->first()->id ?? null;
            $item->save();
        });
    }
}
