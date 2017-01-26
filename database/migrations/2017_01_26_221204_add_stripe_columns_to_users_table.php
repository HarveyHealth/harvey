<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStripeColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->string('stripe_expiry_month', 2)->nullable()->after('stripe_customer_id');
            $table->string('stripe_expiry_year', 4)->nullable()->after('stripe_expiry_month');
            $table->string('stripe_brand')->nullable()->after('stripe_expiry_year');
            $table->string('stripe_last_four', 4)->nullable()->after('stripe_brand');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
