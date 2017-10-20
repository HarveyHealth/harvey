<?php

use App\Models\LabTestInformation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddListOrderToLabTestsInformationTable extends Migration
{
    public function up()
    {
        Schema::table('lab_tests_information', function (Blueprint $table) {
            $table->unsignedSmallInteger('list_order')->after('sku_id')->default(0);
            $table->timestamp('published_at')->after('quote')->nullable();
        });
   
        LabTestInformation::all()->each->update([
            'published_at' => Carbon::now(),
        ]);
    }

    public function down()
    {
        Schema::table('lab_tests_information', function (Blueprint $table) {
            $table->dropColumn('list_order');
        });
    }
}
