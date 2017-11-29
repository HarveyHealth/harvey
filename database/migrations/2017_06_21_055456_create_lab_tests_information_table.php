<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\{LabTestInformation, SKU};

class CreateLabTestsInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_tests_information', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sku_id')->unsigned();
            $table->mediumText('description');
            $table->string('image');
            $table->string('lab_name');
            $table->string('sample');
            $table->text('quote');
            $table->foreign('sku_id')->references('id')->on('skus');
            $table->softDeletes();
            $table->timestamps();
        });

        LabTestInformation::unguard();
        SKU::unguard();

        //Micronutrients
        $sku = SKU::create(['name' => 'Micronutrients', 'item_type' => 'product', 'price' => 299]);
        LabTestInformation::create([
            'sku_id' => $sku->id,
            'description' => '<p>Our micronutrient test is one of the most accurate and scientifically proven method of assessing vitamin, mineral, antioxidant and amino acid deficiencies.</p> <p>Nutritional balance plays a key role in optimal wellness, chronic disease prevention and managing the aging process. If your chronic symptoms include fatigue, stress, depression or irritability, this test can help your doctor quantifiably measure the levels of 35 critical nutritional biomarkers.</p>',
            'image' => '/images/lab_tests/micronutrients.png',
            'lab_name' => $sku->name,
            'sample' => 'Blood draw',
            'quote' => 'Did you know that 9 out of 10 Americans are potassium deficient, 8 out of 10 are vitamin E deficient, 7 out of 10 are calcium deficient, 50% are deficient in vitamin A, vitamin C and magnesium, and 90% of people of color in America are deficient in vitamin D.',
        ]);

        //Hormones
        $sku = SKU::create(['name' => 'Hormones', 'item_type' => 'product', 'price' => 99]);
        LabTestInformation::create([
            'sku_id' => $sku->id,
            'description' => '<p>Our hormone panel is an important test for anyone concerned with changing hormone levels in their body as a result of age. Common symptoms of hormone imbalances include fatigue, insomnia, stress, low libido, immunity or weight imbalances.</p> <p>Like nutrients, hormones influence all aspects of health and disease, including mood, sleep, metabolism, heart health and physical appearance. An imbalance of even one hormone can initiate a cascade of events that can alter additional hormones, so a comprehensive look at your hormone status is vital.</p>',
            'image' => '/images/lab_tests/hormones.png',
            'lab_name' => $sku->name,
            'sample' => 'Blood draw',
            'quote' => 'As women enter menopausal years, their bodies\' production of estrogen and other hormones needed to maintain youthful vitality rapidly declines. Continual assessment of hormone levels is necessary for women seeking to maintain a healthy hormonal balance.',
        ]);

        //Adrenals
        $sku = SKU::create(['name' => 'Adrenals', 'item_type' => 'product', 'price' => 125]);
        LabTestInformation::create([
            'sku_id' => $sku->id,
            'description' => '<p>As we age and our production of sex hormones changes, your adrenals will maintain a central role in sustaining optimal health and function.</p> <p>This panel provides a comprehensive view of adrenal function and includes four cortisol levels timed throughout the day. The morning cortisol level represents the maximum output of cortisol for the entire 24 hour period and initiates and maintains waking day activity and function. Cortisol level at noon, late afternoon, and night indicates the pattern of cortisol production over the 24 hour period and can highlight adrenal exhaustion.</p>',
            'image' => '/images/lab_tests/adrenals.png',
            'lab_name' => $sku->name,
            'sample' => 'Saliva',
            'quote' => 'Are you always tired, anxious, irritable, have body aches, or need caffeine to achieve a basic functioning level in the morning? It may be a result of a hormone imbalance.',
        ]);

        //Thyroid/Cortisol
        $sku = SKU::create(['name' => 'Thyroid/Cortisol', 'item_type' => 'product', 'price' => 99]);
        LabTestInformation::create([
            'sku_id' => $sku->id,
            'description' => '<p>Thyroid hormones directly regulate every cell in our body and affect our most basic functions like metabolism, emotions and thinking. The thyroid test examines several proteins that affect thyroid function and tests for specific thyroid antibodies to help your doctor detect autoimmunity (when the immune system is attacking healthy tissue in your body).</p> <p>Cortisol is a stress hormone that plays a critical role in the metabolism of proteins, lipids, and carbohydrates, among other functions. This test may be necessary when someone has symptoms that suggest adrenal fatigue or Addison disease — such as weight loss, muscle weakness, fatigue, low blood pressure, abdominal pain, depression, sensitivity to cold, constipation or pale dry skin.</p>',
            'image' => '/images/lab_tests/thyroid.png',
            'lab_name' => $sku->name,
            'sample' => 'Blood draw',
            'quote' => 'Over 25 million Americans are diagnosed with a thyroid disorder each year. Thyroid disorders are four times more likely in women than men.',
        ]);

        //CardioMetabolic
        $sku = SKU::create(['name' => 'CardioMetabolic', 'item_type' => 'product', 'price' => 99]);
        LabTestInformation::create([
            'sku_id' => $sku->id,
            'description' => '<p>Cardiovascular disease is the leading cause of death in the United States. Standard cholesterol tests are not sufficient in indicating your risk level (in fact, 50% of people who suffer from heart attacks had normal cholesterol levels).</p> <p>Our cardiometabolic test offers a full clinical evaluation to help define your risk for atherosclerotic cardiovascular disease (ASCVD), inflammation and progression toward Type 2 Diabetes. Results of testing allow doctors to know when guidance, educational referral, or treatment is necessary.</p> <p>This test is recommended for any patient who:</p> <ul><li>Has a family history of heart disease or diabetes</li> <li>Has been diagnosed with heart disease or diabetes</li> <li>Is already taking cholesterol-lowering medications</li> <li>Has been diagnosed with Metabolic Syndrome (high blood pressure)</li> <li>Is significantly overweight</li> <li>Has high LDL (bad cholesterol)</li> <li>Has low HDL (good cholesterol)</li> <li>Has high triglycerides</li></ul>',
            'image' => '/images/lab_tests/cardiometabolic.png',
            'lab_name' => $sku->name,
            'sample' => 'Blood draw',
            'quote' => '50% of people who have a heart attack have normal cholesterol levels. It\'s often the lipoproteins, not the cholesterol, that leads to artery clogging.',
        ]);

        //CBC/CMP
        $sku = SKU::create(['name' => 'CBC/CMP', 'item_type' => 'product', 'price' => 29]);
        LabTestInformation::create([
            'sku_id' => $sku->id,
            'description' => '<p>The CBC panel is the most common blood test available, often used to diagnose anemia, general infection, inflammation and leukemia. Your white blood cells and red blood cells are counted, along with platelets typically with an annual physical.</p> <p>The CMP panel tests liver, kidney and electrolyte biomarkers to make sure your organs are in good working order. These are important tests although most physicians may only perform CMP panels during annual physicals.</p> <p>The CMC/CMP panel is a great add-on for other specialized tests.</p>',
            'image' => '/images/lab_tests/cbc-cmp.png',
            'lab_name' => $sku->name,
            'sample' => 'Blood draw',
            'quote' => 'The CMC/CMP test is the most common blood tests available, which can be easily added to any other specialized lab test.',
        ]);

        //Toxic Metals
        $sku = SKU::create(['name' => 'Toxic Metals', 'item_type' => 'product', 'price' => 199]);
        LabTestInformation::create([
            'sku_id' => $sku->id,
            'description' => '<p>High levels of toxic metals deposited in body tissues and subsequently in the brain has been known to cause significant developmental and neurological damage. Our toxic metals test is the best way to identify the presence and levels of 16 toxic heavy metals (such as mercury, lead, uranium and beryllium) that have been deposited into our body tissues through the foods you eat.</p> <p>Low-level exposure to toxic metals over long periods of time can result in significant adverse health effects and chronic disease. It’s impossible to draw valid conclusions about adverse health effects of toxic metals without assessing your net retention of these metals — meaning the exposure you get from toxic metals vs what your body can properly flush out.</p>',
            'image' => '/images/lab_tests/toxic-metals.png',
            'lab_name' => $sku->name,
            'sample' => 'Urine',
            'quote' => 'Heavy metal toxicity, such as lead in your water or mercury in your fish, has become one of the most pressing health hazards in the country.',
        ]);

        //Toxic Chemicals
        $sku = SKU::create(['name' => 'Toxic Chemicals', 'item_type' => 'product', 'price' => 199]);
        LabTestInformation::create([
            'sku_id' => $sku->id,
            'description' => '<p>Every day, we are exposed to hundreds of toxic chemicals through products like pharmaceuticals, pesticides, packaged foods, household products, and environmental pollution. As we have become more exposed to chemical-laden products and to toxic chemicals in food, air, and water, we have been confronted with an accelerating rate of chronic illnesses.</p> <p>The toxic organics chemicals test is one of the most highly-recommended tests for discovering and eliminating household toxins that are known to cancer, heart disease, chronic fatigue syndrome, chemical sensitivity, autism, ADD/ADHD, autoimmune disorders, Parkinson’s disease and Alzheimer’s disease.</p>',
            'image' => '/images/lab_tests/toxic-chemicals.png',
            'lab_name' => $sku->name,
            'sample' => 'Urine',
            'quote' => 'There are many environmental pollutants linked to serious chronic illnesses that can be found in the air, water and products in your home.',
        ]);

        //Food Allergy
        $sku = SKU::create(['name' => 'Food Allergy', 'item_type' => 'product', 'price' => 199]);
        LabTestInformation::create([
            'sku_id' => $sku->id,
            'description' => '<p>Individuals with neurological, gastrointestinal and movement disorders often suffer from food allergies. Many people continue to eat offending foods every day for years, unaware of their potential effects. Our food allergy test helps identify even the most hypersensitive of allergies caused by over 93 different foods across multiple food groups, including dairy, fruit, fish, meat, nuts, seeds, grains, beans and vegetables.</p> <p>According to numerous clinical studies, elimination of allergenic food groups can vastly improve symptoms of irritable bowel syndrome, autism, ADD/ADHD, cystic fibrosis, rheumatoid arthritis,and epilepsy. Food rotation and elimination diets can help reduce stress on the immune system, lower gut inflammation, resolve food cravings, and reduce the propensity for eating disorders.</p>',
            'image' => '/images/lab_tests/food-allergies.png',
            'lab_name' => $sku->name,
            'sample' => 'Blood draw',
            'quote' => 'There are eight types of food that account for majority of food allergies. We test those foods plus 85 others to find the ones you probably missed.',
        ]);

        //Microbiome (Gut)
        $sku = SKU::create(['name' => 'Microbiome (Gut)', 'item_type' => 'product', 'price' => 199]);
        LabTestInformation::create([
            'sku_id' => $sku->id,
            'description' => '<p>Our microbiome test identifies specific pathogens and other microorganisms in your gut such as yeast, parasites and bacteria that may be affecting your health. The test can measure your bacterial diversity and other useful metrics about your microbiome. It evaluates intestinal immune function, overall intestinal health, inflammation markers and levels of good bacteria in your body which may be missed by micronutrient and other tests.</p> <p>Many chronic disorders, especially neurological dysfunction, come from digestive problems and inadequate nutrient absorption. Nutrients require a specific internal environment to be properly digested and transported throughout the body.</p>',
            'image' => '/images/lab_tests/microbiome.png',
            'lab_name' => $sku->name,
            'sample' => 'Stool',
            'quote' => 'The gut is your largest endocrine organ that contains 70% of your immune system and is almost as important as your heart and brain.',
        ]);

        //Organic Acids
        $sku = SKU::create(['name' => 'Organic Acids', 'item_type' => 'product', 'price' => 299]);
        LabTestInformation::create([
            'sku_id' => $sku->id,
            'description' => '<p>Our organic acids test offers a comprehensive metabolic snapshot of a your overall health with over 70 biomarkers. Specifically, it provides an accurate evaluation of intestinal yeast and bacteria. Abnormally high levels of these microorganisms can cause or worsen behavior disorders, hyperactivity, movement disorders, fatigue and immune dysfunction.</p> <p>Many people with chronic illnesses and neurological disorders often excrete abnormal organic acids in their urine, which can be easily detected here.</p>',
            'image' => '/images/lab_tests/organic-acids.png',
            'lab_name' => $sku->name,
            'sample' => 'Urine',
            'quote' => 'Malabsorption is a condition that prevents vitamans, proteins, sugars, fats and other food to be absorbed into the small intestine, leading to chronic digestive problems, nutritional deficiencies, yeast infections, cognitive impairment and other degenerative conditions.',
        ]);

        LabTestInformation::reguard();
        SKU::reguard();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lab_tests_information');
    }
}
