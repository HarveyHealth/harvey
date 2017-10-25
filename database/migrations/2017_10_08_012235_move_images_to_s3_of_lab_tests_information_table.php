<?php

use App\Models\LabTestInformation;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $item->image = 'https://harvey-production.s3.amazonaws.com/assets' . $item->image;
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
            $item->image = str_replace('https://harvey-production.s3.amazonaws.com/assets/', '', $item->image);
            $item->save();
        });
    }
}
