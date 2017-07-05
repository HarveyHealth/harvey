<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddressColumnsToLabOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lab_orders', function (Blueprint $table) {
            $table->string('zip', 10)->after('shipment_code')->nullable();
            $table->string('state', 2)->after('shipment_code')->nullable();
            $table->string('city', 100)->after('shipment_code')->nullable();
            $table->string('address_2', 100)->after('shipment_code')->nullable();
            $table->string('address_1', 100)->after('shipment_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lab_orders', function (Blueprint $table) {
            $table->dropColumn([
                'address_1',
                'address_2',
                'city',
                'state',
                'zip',
            ]);
        });
    }
}
