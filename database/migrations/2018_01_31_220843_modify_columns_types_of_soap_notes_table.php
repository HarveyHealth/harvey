<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyColumnsTypesOfSoapNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('soap_notes', function (Blueprint $table) {
            $table->string('subjective', 16777215)->nullable()->change();
            $table->string('objective', 16777215)->nullable()->change();
            $table->string('assessment', 16777215)->nullable()->change();
            $table->string('plan', 16777215)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('soap_notes', function (Blueprint $table) {
            $table->string('subjective', 65535)->change();
            $table->string('objective', 65535)->change();
            $table->string('assessment', 65535)->change();
            $table->string('plan', 65535)->change();
        });
    }
}
