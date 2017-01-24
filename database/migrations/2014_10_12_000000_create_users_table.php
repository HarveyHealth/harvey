<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('enabled')->default(true)->index();
            $table->string('password');
            $table->string('first_name');
            $table->string('last_name')->index();
            $table->string('image_url')->nullable();
            $table->string('email')->unique();
            $table->dateTime('email_verified_at')->nullable();
            $table->string('phone', 15)->nullable()->unique();
            $table->dateTime('phone_verified_at')->nullable();
            $table->string('address_1', 100)->nullable();
            $table->string('address_2', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 2)->nullable();
            $table->string('zip', 10)->nullable();
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
            $table->string('timezone', 75)->nullable();
            $table->date('birthdate')->nullable();
            $table->string('gender')->nullable();
            $table->smallInteger('height_feet')->unsigned()->nullable();
            $table->smallInteger('height_inches')->unsigned()->nullable();
            $table->smallInteger('weight')->unsigned()->nullable();
            $table->json('symptoms')->nullable();
            $table->string('stripe_customer_id', 32)->nullable()->unique();
            $table->datetime('terms_accepted_at')->nullable();
            $table->rememberToken();
            $table->char('api_token', 60)->nullable()->unique();
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
        Schema::dropIfExists('users');
    }
}
