<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddParcelInfoToSkusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('skus', function (Blueprint $table) {
            $table->decimal('weight')->after('slug')->nullable();
            $table->decimal('height')->after('slug')->nullable();
            $table->decimal('width')->after('slug')->nullable();
            $table->decimal('length')->after('slug')->nullable();
            $table->string('distance_unit', 2)->after('slug')->nullable();
            $table->string('mass_unit', 2)->after('slug')->nullable();
        });

        \App\Models\SKU::all()->map(function($sku) {
            if ($sku->item_type === 'lab-test') {
                $sku->weight = '2';
                $sku->height = '12';
                $sku->width = '4';
                $sku->length = '12';
                $sku->distance_unit = 'in';
                $sku->mass_unit = 'lb';
                $sku->save();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('skus', function (Blueprint $table) {
            $table->dropColumn(['weight', 'height', 'width', 'length', 'distance_unit', 'mass_unit']);
        });
    }
}
