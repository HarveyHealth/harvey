<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Practitioner;

class ModifyGraduatedAtColumnOfPractitionersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('json', 'string');
    
        Schema::table('practitioners', function (Blueprint $table) {
            $table->renameColumn('graduated_at', 'graduated_year');
        });
    
        Schema::table('practitioners', function (Blueprint $table) {
            $table->string('graduated_year')->nullable()->change();
        });
    
        Practitioner::all()->each(function ($item, $key) {
            $item->graduated_year = Carbon::parse($item->graduated_year)->format('Y');
            $item->save();
        });
    
        Schema::table('practitioners', function (Blueprint $table) {
            $table->integer('graduated_year')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('json', 'string');
    
        Schema::table('practitioners', function (Blueprint $table) {
            $table->string('graduated_year')->nullable()->change();
        });
    
        Practitioner::all()->each(function ($item, $key) {
            $item->graduated_year = empty($item->graduated_year) ? null : Carbon::parse("{$item->graduated_year}-01-01 00:00:00")->toDateTimeString();
            $item->save();
        });
    
        Schema::table('practitioners', function (Blueprint $table) {
            $table->dateTime('graduated_year')->nullable()->change();
        });
    
        Schema::table('practitioners', function (Blueprint $table) {
            $table->renameColumn('graduated_year', 'graduated_at');
        });
    }
}
