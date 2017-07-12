<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropLicenseTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('licenses');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('licenses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 3);
            $table->integer('number')->unsigned()->nullable();
            $table->string('state', 20);
            $table->timestamps();
        });
    }
}
