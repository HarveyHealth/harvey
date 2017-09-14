<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id')->unsigned();
            $table->foreign('patient_id')->references('id')->on('patients');
            $table->text('description');
            $table->string('status')->default('pending');
            $table->integer('transaction_id')->unsigned()->nullable();
            $table->foreign('transaction_id')->references('id')->on('transactions');
            $table->datetime('paid_on')->nullable();
            $table->string('cc_last_four', 4)->nullable();
            $table->decimal('subtotal')->default(0);
            $table->integer('discount_code_id')->unsigned()->nullable();
            $table->foreign('discount_code_id')->references('id')->on('discount_codes');
            $table->decimal('discount')->default(0);
            $table->decimal('amount')->default(0);
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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('invoices');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

    }
}
