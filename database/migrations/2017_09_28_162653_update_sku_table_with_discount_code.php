<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSkuTableWithDiscountCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discount_codes', function (Blueprint $table) {
            $table->string('applies_to')->default('all')->after('code');
        });

        $sku = new \App\Models\SKU;
        $sku->name = 'Discount';
        $sku->price = 0.00;
        $sku->item_type = 'discount';
        $sku->slug = 'discount';
        $sku->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('skus', function (Blueprint $table) {
            //
        });
    }
}
