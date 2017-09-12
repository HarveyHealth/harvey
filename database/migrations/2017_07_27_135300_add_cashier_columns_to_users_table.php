<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCashierColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->timestamp('trial_ends_at')->after('password')->nullable();
            $table->string('stripe_id')->after('password')->nullable();
            $table->string('card_last_four')->after('password')->nullable();
            $table->string('card_brand')->after('password')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'trial_ends_at',
                'stripe_id',
                'card_last_four',
                'card_brand',
            ]);
        });
    }
}
