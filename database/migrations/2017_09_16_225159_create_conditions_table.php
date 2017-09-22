<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conditions', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('enabled')->default(true);
            $table->string('name')->unique();
            $table->string('slug')->unique()->index();
            $table->string('image_url');
            $table->longText('description');
            $table->json('questions');
            $table->timestamps();
        });

        $conditions = [
             [
                'name' => 'Allergies',
                'slug' => 'allergies',
                'image_url' => 'condition_allergies',
                'description' => 'Allergic symptoms are thought to affect 50 million people in the United States. Allergic symptoms are your immune system’s extreme response to substances that are normally found, in your everyday environment. Very often, simple changes of diet, nutritional supplements, and herbal remedies can relieve this extreme reaction and the resulting inflammation that triggers most allergy symptoms.',
                'questions' => [
                    [
                        'question' => 'What are your allergies caused by?',
                        'answers' => ['Food', 'Weather', 'Both', 'Other']
                    ],
                    [
                        'question' => 'How long have you had these symptoms?',
                        'answers' => ['Past month', 'Past six months', 'Six months or longer']
                    ],
                    [
                        'question' => 'Do you have any pets?',
                        'answers' => ['Yes', 'No']
                    ],
                    [
                        'question' => 'How often do you take allergy medication, if at all?',
                        'answers' => ['Daily', 'Few time a month', 'Only as needed', 'Never']
                    ],
                    [
                        'question' => 'Have you ever had any food allergy tests done?',
                        'answers' => ['Yes', 'No', "I don't remember"]
                    ]
                ]
            ],
          //   [
          //      'name' => 'Skin Conditions',
          //      'slug' => 'skin-conditions',
          //      'image_url' => 'condition_skin_conditions',
          //      'description' => "A traditional medical approach for skin complaints typically will treat the symptom, and approach the issue topically with creams, medications, etc. To look at a skin condition from an holistic approach, we try to address root causes of skin complaints by looking at dietary related skin exacerbations, gut health and efficiency of detoxification pathways.",
          //      'questions' => [
          //          [
          //              'question' => "What type of skin conditions affect you the most?",
          //              'answers' => ['Acne', 'Eczema', 'Psoriasis', 'Rash', 'Other']
          //          ],
          //          [
          //              'question' => "How long have you had these symptoms?",
          //              'answers' => ['Past month', 'Past six months', 'Six months or longer']
          //          ],
          //          [
          //              'question' => "Do you currently take any medication for your condition?",
          //              'answers' => ['Yes', 'No']
          //          ],
          //          [
          //              'question' => "Does your skin condition change through the month?",
          //              'answers' => ['Yes', 'No', 'Not sure']
          //          ],
          //          [
          //              'question' => "Do you feel like your skin condition is affected by your diet?",
          //              'answers' => ['Yes', 'No', 'Not sure']
          //          ],
          //          [
          //              'question' => "Have you ever had any allergy tests done?",
          //              'answers' => ['Yes', 'No', "I don't remember"]
          //          ],
          //      ]
          //  ],
          //  [
          //      'name' => "Digestive Issues",
          //      'slug' => "digestive-issues",
          //      'image_url' => "condition_digestive_issues",
          //      'description' => "Your digestive system is one of the most important systems in your whole body. If you cannot properly digest and absorb nutrients and eliminate waste products, then it’s nearly impossible to achieve optimal health. Proper gut function is critical to addressing what can be seemingly unrelated conditions. Our approach to gut health may include assessing for food sensitivity, testing the quality of your microbiome and supporting optimal digestion with nutrition, herbs and supplementation.",
          //      'questions' => [
          //          [
          //               'question' => "What digestion issue affects you the most?",
          //               'answers' => ["Upset stomach/diarrhea", "Gas/bloating", "Constipation", "Pain in stomach", "Other"]
          //          ],
          //          [
          //               'question' => "Is the condition heightened by specific foods or is it pretty consistent?",
          //               'answers' => ["Irritated by foods", "Consistent no matter what I eat"]
          //          ],
          //          [
          //               'question' => "Do you take any probiotics consistently, and if so, how much?",
          //               'answers' => ["Yes - 5 billion cfu or less", "Yes - 5-25 billion cfu", "Yes - 25 billion or more cfu", "No", "I don't know"]
          //           ],
          //           [
          //               'question' => "Do you take any probiotics consistently, and if so, how much?",
          //               'answers' => ["Yes - 5 billion cfu or less", "Yes - 5-25 billion cfu", "Yes - 25 billion or more cfu", "No", "I don't know"]
          //           ],
          //           [
          //               'question' => "What is your average daily stress level?",
          //               'answers' => ["Low stress", "Moderate stress", "High stress"]
          //           ],
          //           [
          //               'question' => "How often would you say your digestive issues affect you?",
          //               'answers' => ["Once a week", "Few times a week", "Every day", "Few times a month"]
          //           ],
          //           [
          //               'question' => "What severity would you rate the symptoms?",
          //               'answers' => ["Annoying but I manage easily", "Painful, but i get by on my day", "Takes me out of action some days"]
          //           ],
          //           [
          //               'question' => "Have you ever tested your food sensitivities or the health of your microbiome?",
          //               'answers' => ["Yes", "No", "Not sure"]
          //           ]
          //       ]
          //   ]
        ];

        foreach ($conditions as &$condition) {
            $newCondition = new \App\Models\Condition;
            $newCondition->enabled = true;
            $newCondition->name = $condition['name'];
            $newCondition->slug = $condition['slug'];
            $newCondition->image_url = $condition['image_url'];
            $newCondition->description = $condition['description'];
            $newCondition->questions = json_encode($condition['questions']);
            $newCondition->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conditions');
    }
}
