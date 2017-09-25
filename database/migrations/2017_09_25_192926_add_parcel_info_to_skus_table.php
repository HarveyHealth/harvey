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
            $table->text('weight')->after('slug')->nullable();
            $table->text('height')->after('slug')->nullable();
            $table->text('width')->after('slug')->nullable();
            $table->text('length')->after('slug')->nullable();
            $table->text('distance_unit')->after('slug')->nullable();
            $table->text('mass_unit')->after('slug')->nullable();
        });

        // make sure only lab tests get dimensions
        foreach (\App\Models\SKU::all() as $sku) {
            if ($sku->item_type === 'lab-test') {
                $sku->weight = '2';
                $sku->height = '12';
                $sku->width = '4';
                $sku->length = '12';
                $sku->distance_unit = 'in';
                $sku->mass_unit = 'lb';
                $sku->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('skus', function (Blueprint $table) {
            $table->dropColumn('weight');
            $table->dropColumn('height');
            $table->dropColumn('width');
            $table->dropColumn('length');
            $table->dropColumn('distance_unit');
            $table->dropColumn('mass_unit');
        });
    }
}
