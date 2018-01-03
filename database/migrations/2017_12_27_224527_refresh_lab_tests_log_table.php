<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use App\Models\LabTest;

class RefreshLabTestsLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists(LabTest::getLogTableName());
        Schema::create(LabTest::getLogTableName(), LabTest::getLogTableSchema());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(LabTest::getLogTableName());
    }
}