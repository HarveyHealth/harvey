<?php

use App\Models\LabTestInformation;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExampleToLabTestsInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lab_tests_information', function (Blueprint $table) {
            $table->string('example')->after('image')->nullable();
        });

        $base_path = 'assets/pdfs/lab-test-examples/';

        $examples = [
           'Micronutrients' => 'spectracell-micronutrient.pdf',
           'Hormones' => 'spectracell-hormone.pdf',
           'Adrenals' => 'labrix-adrenals.pdf',
           'Thyroid/Cortisol' => 'spectracell-thyroid.pdf',
           'CardioMetabolic' => 'spectracell-cardiometabolic.pdf',
           'CBC/CMP' => 'spectracell-cmp-cbc.pdf',
           'Toxic Metals' => 'great-plains-toxic-metals.pdf',
           'Toxic Chemicals' => 'great-plains-toxic-chemicals.pdf',
           'Food Allergy' => 'great-plains-food-allergy.pdf',
           'Microbiome (Gut)' => 'great-plains-microbiome.pdf',
           'Organic Acids' => 'great-plains-organic-acids.pdf',
        ];

        foreach ($examples as $test_name => $test_example) {
            $function = function ($lti) use ($test_name) { return $lti->sku->name == $test_name; };
            if ($lab_test_information = LabTestInformation::all()->filter($function)->first()) {
                $lab_test_information->example = cloudfront_link($base_path.$test_example);
                $lab_test_information->save();
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
        Schema::table('lab_tests_information', function (Blueprint $table) {
            $table->dropColumn('example');
        });
    }
}
