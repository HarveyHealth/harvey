<?php

use App\Models\LabTestInformation;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MoveImagesToCloudfrontOfLabTestsInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        LabTestInformation::all()->map(function ($item) {
            $item->image = str_replace('harvey-production.s3.amazonaws.com', 'd35oe889gdmcln.cloudfront.net', $item->image);
            $item->save();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        LabTestInformation::all()->map(function ($item) {
            $item->image = str_replace('d35oe889gdmcln.cloudfront.net', 'harvey-production.s3.amazonaws.com', $item->image);
            $item->save();
        });
    }
}
