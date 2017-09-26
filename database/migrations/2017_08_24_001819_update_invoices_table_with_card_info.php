<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateInvoicesTableWithCardInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('cc_last_four');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->string('card_brand')->nullable()->after('paid_on');
            $table->string('card_last_four', 4)->nullable()->after('card_brand');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('card_last_four');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('card_brand');
        });
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('cc_last_four', 4)->nullable();
        });
    }
}
