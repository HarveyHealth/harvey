<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropStripeColumnsFromPatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'stripe_customer_id',
                'stripe_expiry_month',
                'stripe_expiry_year',
                'stripe_brand',
                'stripe_last_four',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('stripe_customer_id', 32)->nullable()->unique();
            $table->string('stripe_expiry_month', 2)->nullable();
            $table->string('stripe_expiry_year', 4)->nullable();
            $table->string('stripe_brand')->nullable();
            $table->string('stripe_last_four', 4)->nullable();
        });
    }
}
