<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\License;

class AddTitleColumnToLicensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('licenses', 'title')) {
            Schema::table('licenses', function (Blueprint $table) {
                $table->string('title', 3)->after('id')->nullable();
            });
        }

        License::all()->each(function ($item, $key) {
            $pieces = explode('-', $item->number);

            if (2 == count($pieces)) {
                $item->title = $pieces[0];
                $item->number = $pieces[1];
            } else {
                $item->number = empty($pieces[0]) ? '00000' : $pieces[0];
                $item->title = 'XX';
            }

            $item->save();
        });

        if (License::all()->every(function ($value, $key) { return !empty($value->title); })) {
            Schema::table('licenses', function (Blueprint $table) {
                $table->string('title')->nullable(false)->change();
            });
        }

        if (License::all()->every(function ($value, $key) { return is_numeric($value->number); })) {
            Schema::table('licenses', function (Blueprint $table) {
                $table->integer('number')->unsigned()->nullable(false)->change();
            });
        }

        if (License::all()->every(function ($value, $key) { return !empty($value->state); })) {
            Schema::table('licenses', function (Blueprint $table) {
                $table->string('state', 2)->nullable(false)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('licenses', function (Blueprint $table) {
            $table->string('number')->change();
        });

        License::all()->each(function ($item, $key) {
            $item->number = "{$item->title}-{$item->number}";
            $item->save();
        });

        Schema::table('licenses', function (Blueprint $table) {
            $table->dropColumn('title');
        });

    }
}
