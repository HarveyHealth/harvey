<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvailableLabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('available_labs', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('enabled')->default(true)->index();
            $table->string('lab_group', 100)->nullable()->index();
            $table->string('lab_id',20)->nullable();
            $table->text('lab_name')->nullable();
            $table->boolean('mobile')->default(false)->index();
            $table->string('phone', 15)->nullable();
            $table->string('address_1', 100)->nullable();
            $table->string('address_2', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 2)->nullable();
            $table->string('zip', 10)->nullable();
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
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
        Schema::dropIfExists('available_labs');
    }
}
