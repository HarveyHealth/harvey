<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\LabTestInformation;

class MoveImagesToS3OfLabTestsInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        LabTestInformation::all()->map(function ($item) {
            $item->image = 'https://d35oe889gdmcln.cloudfront.net/assets' . $item->image;
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
            $item->image = str_replace('https://d35oe889gdmcln.cloudfront.net/assets/', '', $item->image);
            $item->save();
        });
    }
}
