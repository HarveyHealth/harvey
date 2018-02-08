<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Lib\Clients\Typeform;
use App\Models\{Intake, Patient};

class DropIntakeTokenColumnOfPatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $migrate_to_intakes_table = function ($patient) {
            $data = Typeform::getDataForToken($token = $patient->intake_token);
            $patient->user->intake()->create(['token' => $token, 'data' => $data]);
        };

        Patient::whereNotNull('intake_token')->each($migrate_to_intakes_table);

        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn('intake_token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('intake_token')->after('symptoms')->nullable();
        });
    }
}
