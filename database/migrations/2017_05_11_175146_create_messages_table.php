<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sender_user_id')->unsigned();
            $table->foreign('sender_user_id')->references('id')->on('users');
            $table->integer('recipient_user_id')->unsigned();
            $table->foreign('recipient_user_id')->references('id')->on('users');
            $table->text('message')->nullable();
            $table->text('upload_url')->nullable();
            $table->boolean('is_admin')->default(false);
            $table->dateTime('read_at')->nullable();
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
        Schema::dropIfExists('messages');
    }
}
