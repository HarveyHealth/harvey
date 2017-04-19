<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('enabled')->default(true)->index();
            $table->integer('user_id')->unsigned();
            $table->date('birthdate')->nullable();
            $table->smallInteger('height_feet')->unsigned()->nullable();
            $table->smallInteger('height_inches')->unsigned()->nullable();
            $table->smallInteger('weight')->unsigned()->nullable();
            $table->json('symptoms')->nullable();
            $table->string('stripe_customer_id', 32)->nullable()->unique();
            $table->string('stripe_expiry_month', 2)->nullable();
            $table->string('stripe_expiry_year', 4)->nullable();
            $table->string('stripe_brand')->nullable();
            $table->string('stripe_last_four', 4)->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patients');
    }
}
