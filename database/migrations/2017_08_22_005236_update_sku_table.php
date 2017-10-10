<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSkuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('skus', function (Blueprint $table) {
            $table->dropColumn('item_type');
        });

        Schema::table('skus', function (Blueprint $table) {
            $table->string('item_type')->default('lab-test')->after('price');
            $table->string('slug')->unique()->nullable()->after('item_type');
        });

        // update all SKUs with slugs
        foreach (\App\Models\SKU::all() as $sku) {
            $sku->save();
        }

        // add consultations
        $items = [30, 60];
        foreach ($items as $item) {
            $sku = new \App\Models\SKU;
            $sku->name = $item . ' Minute Consultation';
            $sku->price = ($item == 30 ? 75.00 : 150.00);
            $sku->item_type = 'consultation';
            $sku->save();
        }

        $sku = new \App\Models\SKU;
        $sku->name = 'Processing Fee';
        $sku->price = 20.00;
        $sku->item_type = 'service-fee';
        $sku->slug = 'processing-fee-self';
        $sku->save();

        $sku = new \App\Models\SKU;
        $sku->name = 'Shipping';
        $sku->price = 0.00;
        $sku->item_type = 'service-fee';
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
            $table->dropColumn('item_type');
        });

        Schema::table('skus', function (Blueprint $table) {
            $table->enum('item_type', ['test','product','service'])->after('name')->nullable()->index();
        });

        Schema::table('skus', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
}
