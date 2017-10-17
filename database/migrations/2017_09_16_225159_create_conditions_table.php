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
            [
                'name' => 'Skin Conditions',
                'slug' => 'skin-conditions',
                'image_url' => 'condition_skin_conditions',
                'description' => "A traditional medical approach for skin complaints typically will treat the symptom, and approach the issue topically with creams, medications, etc. To look at a skin condition from an holistic approach, we try to address root causes of skin complaints by looking at dietary related skin exacerbations, gut health and efficiency of detoxification pathways.",
                'questions' => [
                    [
                        'question' => "What type of skin conditions affect you the most?",
                        'answers' => ['Acne', 'Eczema', 'Psoriasis', 'Rash', 'Other']
                    ],
                    [
                        'question' => "How long have you had these symptoms?",
                        'answers' => ['Past month', 'Past six months', 'Six months or longer']
                    ],
                    [
                        'question' => "Do you currently take any medication for your condition?",
                        'answers' => ['Yes', 'No']
                    ],
                    [
                        'question' => "Does your skin condition change through the month?",
                        'answers' => ['Yes', 'No', 'Not sure']
                    ],
                    [
                        'question' => "Do you feel like your skin condition is affected by your diet?",
                        'answers' => ['Yes', 'No', 'Not sure']
                    ],
                    [
                        'question' => "Have you ever had any allergy tests done?",
                        'answers' => ['Yes', 'No', "I don't remember"]
                    ],
                ]
            ],
            [
                'name' => "Digestive Issues",
                'slug' => "digestive-issues",
                'image_url' => "condition_digestive_issues",
                'description' => "Your digestive system is one of the most important systems in your whole body. If you cannot properly digest and absorb nutrients and eliminate waste products, then it’s nearly impossible to achieve optimal health. Proper gut function is critical to addressing what can be seemingly unrelated conditions. Our approach to gut health may include assessing for food sensitivity, testing the quality of your microbiome and supporting optimal digestion with nutrition, herbs and supplementation.",
                'questions' => [
                    [
                        'question' => "What digestion issue affects you the most?",
                        'answers' => ["Upset stomach/diarrhea", "Gas/bloating", "Constipation", "Pain in stomach", "Other"]
                    ],
                    [
                        'question' => "Is the condition heightened by specific foods or is it pretty consistent?",
                        'answers' => ["Irritated by foods", "Consistent no matter what I eat"]
                    ],
                    [
                        'question' => "Do you take any probiotics consistently, and if so, how much?",
                        'answers' => ["Yes - 5 billion cfu or less", "Yes - 5-25 billion cfu", "Yes - 25 billion or more cfu", "No", "I don't know"]
                    ],
                    [
                        'question' => "What is your average daily stress level?",
                        'answers' => ["Low stress", "Moderate stress", "High stress"]
                    ],
                    [
                        'question' => "How often would you say your digestive issues affect you?",
                        'answers' => ["Once a week", "Few times a week", "Every day", "Few times a month"]
                    ],
                    [
                        'question' => "What severity would you rate the symptoms?",
                        'answers' => ["Annoying but I manage easily", "Painful, but I get by on my day", "Takes me out of action some days"]
                    ],
                    [
                        'question' => "Have you ever tested your food sensitivities or the health of your microbiome?",
                        'answers' => ["Yes", "No", "Not sure"]
                    ]
                ]
            ],
            [
                'name' => "Hormone Imbalances",
                'slug' => "hormone-imbalances",
                'image_url' => 'condition_hormone_imbalances',
                'description' => "An hormonal imbalance is a root cause of many different health issues. Symptoms of hormone imbalance are extremely varied, and may cover things like irregular menses, PMS and menopause, to more seemingly unrelated symptoms like fatigue, poor stress management and skin issues. Many people experience symptoms of hormonal imbalance without even realizing the true cause. Assessment of hormone levels and adrenal health are possible starting points for a natural approach to hormone balance for optimal wellness.",
                'questions' => [
                    [
                        'question' => "Do you regularly feel irritable and moody?",
                        'answers' => ["Yes", "No"]
                    ],
                    [
                        'question' => "How often do you have low energy?",
                        'answers' => ["Daily", "Few times a week", "Few times a month"]
                    ],
                    [
                        'question' => "What is your average daily stress level?",
                        'answers' => ["Low stress", "Moderate stress", "High stress"]
                    ],
                    [
                        'question' => "How would you describe your libido?",
                        'answers' => ["Low", "Average", "High"]
                    ],
                    [
                        'question' => "Are you getting colds/sick very easily?",
                        'answers' => ["Yes", "No"]
                    ],
                    [
                        'question' => "Have you ever had a hormone or adrenal panel done?",
                        'answers' => ["Yes", "No", "I don't know"]
                    ]
                ]
            ],
            [
                'name' => "Weight Loss",
                'slug' => "weight-loss",
                'image_url' => "condition_weight_loss",
                'description' => "Losing weight is an extremely individualized process. Each person will respond differently to the wide array of lifestyle options, exercise, diets and plans that exist. Naturopathic doctors specialize in treatment of factors, which inhibit weight loss including: specific dietary counseling, promoting proper digestion, restoring compromised liver function, promoting proper thyroid metabolism, identifying nutrient deficiencies, treating food allergies, treating insulin resistance, detoxification of heavy metals, and treatment of intestinal imbalances. We will formulate an individualized plan that works for you and your lifestyle needs to help you feel the way you deserve to feel about yourself.",
                'questions' => [
                    [
                        'question' => "How long have you had a problem with your weight?",
                        'answers' => ["Fairly recent", "Under a year", "For as long as I can remember"]
                    ],
                    [
                        'question' => "Do you have a strict diet?",
                        'answers' => ["Yes - I spend a lot of time/energy on food planning", "I usually eat pretty healthy but not every day", "Not yet"]
                    ],
                    [
                        'question' => "How often do you exercise?",
                        'answers' => ["Almost every day", "Sometimes", "Never"]
                    ],
                    [
                        'question' => "How much sugar / simple carbs do you consume?",
                        'answers' => ["Quite a bit", "Average", "Very litte"]
                    ],
                    [
                        'question' => "Are you familiar with Intermittent Fasting?",
                        'answers' => ["Yes and I've done it", "Heard of it, but have not tried", "Don't know about it"]
                    ],
                    [
                        'question' => "Have you had your hormones and thyroid checked in the past?",
                        'answers' => ["Hormones", "Thyroid", "Both", "Neither"]
                    ]
                ]
            ],
            [
                'name' => "Fertility",
                'slug' => 'fertility',
                'image_url' => 'condition_fertility',
                'description' => "The natural approach to treating infertility aims to address its root causes, by addressing all of the body systems, rather than just focusing solely on the reproductive system. There are numerous underlying conditions and factors that can make pregnancy more difficult. This may include micronutrient status, detoxification efficiency and stress. These variables may not be the sole factor affecting fertility, but may have an additive effect on reducing probability of successful conception. By working to address and eliminate underlying factors, we are addressing the whole person, and negating variables in conception success.",
                'questions' => [
                    [
                        'question' => "How long have you already been trying to conceive?",
                        'answers' => ["1-6 months", "6-12 months", "Over 12 months"]
                    ],
                    [
                        'question' => "When are you trying to conceive?",
                        'answers' => ["Currently", "Next 6-12 months", "After 12 months"]
                    ],
                    [
                        'question' => "Have you seen a fertility specialist thus far?",
                        'answers' => ["Yes", "No"]
                    ],
                    [
                        'question' => "Have you had a comprehensive hormone panel?",
                        'answers' => ["Yes", "No"]
                    ],
                    [
                        'question' => "What is your greatest fertility concern?",
                        'answers' => ["Hormone balance", "Nutrient status", "Presence of toxicity"]
                    ],
                    [
                        'question' => "Have you been tested for toxic metals in your bloodstream?",
                        'answers' => ["Yes", "No"]
                    ]
                ]
            ],
            [
                'name' => "Fibromyalgia and Fatigue",
                'slug' => "fibromyalgia-and-fatigue",
                'image_url' => 'condition_fibromyalgia_and_fatigue',
                'description' => "Fatigue is one of the most common reasons for people in the United States to seek medical help. Oftentimes, a traditional approach will rule out underlying conditions which are common causes of fatigue, and then hit a dead end. From a Naturopathic perspective, fatigue should be assessed as a whole body approach, and multiple contributing variables to fatigue will be addressed. Dietary factors, nutrient support, adrenal health/hormone balance will all be taken into account when creating a care plan for fatigue management and optimal energy support.",
                'questions' => [
                    [
                        'question' => "Do you have problems sleeping?",
                        'answers' => ["Yes", "No", "Only sometimes"]
                    ],
                    [
                        'question' => "How would you describe your pain?",
                        'answers' => ["Localized to only certain body parts", "All over my body", "Little pain, if any"]
                    ],
                    [
                        'question' => "How physically strenuous/demanding is your day-to-day life?",
                        'answers' => ["Very demanding", "Moderately demanding", "Not very demanding"]
                    ],
                    [
                        'question' => "Does your fatigue keep you from doing what you need on a day-to-day basis?",
                        'answers' => ["All the time", "Sometimes", "Never"]
                    ],
                    [
                        'question' => "Do you get more tired if you have not eaten, or does eating make you sleepy?",
                        'answers' => ["Tired without food", "Tired from eating", "Neither"]
                    ],
                    [
                        'question' => "Have you ever checked your adrenal status or micronutrient levels?",
                        'answers' => ["Yes", "No", "Not sure"]
                    ]
                ]
            ],
            [
                'name' => "Diet and Nutrition Guidance",
                'slug' => "diet-and-nutrition",
                'image_url' => "condition_diet_and_nutrition",
                'description' => "One of the core beliefs in Naturopathic medicine is based on letting food be your medicine, and letting your medicine be your food. Maybe you feel pretty great, and you just want a little extra guidance on optimizing your diet? Maybe you follow a vegetarian diet and you want to be sure you’re doing the best you can for your body? We can approach optimal diet by removing food sensitivities, assessing and supporting nutrient balance, and removing obstacles to optimal wellness.",
                'questions' => [
                    [
                        'question' => "What are your goals in working with us?",
                        'answers' => ["Learn about personalized nutrition", "Supplement advice (vitamins, minerals, botanicals)"]
                    ],
                    [
                        'question' => "Are you vegetarian or vegan?",
                        'answers' => ["Vegetarian", "Vegan", "No"]
                    ],
                    [
                        'question' => "Do you follow any of the following dietary guidelines?",
                        'answers' => ["Plant based", "Paleo", "Gluten free", "Ketogenic", "Other", "None"]
                    ],
                    [
                        'question' => "What are your goals for nutritional guidance?",
                        'answers' => ["Better energy", "Weight loss", "General wellness"]
                    ],
                    [
                        'question' => "Do you have specific food allergies?",
                        'answers' => ["Yes", "No", "I'm not sure"]
                    ],
                    [
                        'question' => "Have you ever taken a micronutrient test?",
                        'answers' => ["Yes", "No", "I don't remember"]
                    ],
                ]
            ],
            [
                'name' => "Anxiety and Depression",
                'slug' => "anxiety-and-depression",
                'image_url' => "condition_anxiety_and_depression",
                'description' => "Naturopathic medicine strongly believes in “whole person wellness”. This includes mind, body and spirit. If our mind or spirit is not well, it affects us physically, and vice versa. Additional investigation may include nutrient status, hormone balance and diet assessments. We have effective, non-toxic therapies to help you find the balance you need to feel as healthy as you’d like to feel.",
                'questions' => [
                    [
                        'question' => "Do you often feel depressed or anxious?",
                        'answers' => ["Depressed", "Anxious", "Both", "Neither"]
                    ],
                    [
                        'question' => "How long have you felt these symptoms?",
                        'answers' => ["Recent (past six months)", "Intermediate term (six months to twelve months)", "Long-term (over one year)"]
                    ],
                    [
                        'question' => "How often does your condition affect your daily life?",
                        'answers' => ["Always", "Sometimes", "Never"]
                    ],
                    [
                        'question' => "Is your conditions linked to a specific event/experience (i.e. job issue, relationship loss, etc.)",
                        'answers' => ["Yes", "No - it's consistent no matter what"]
                    ],
                    [
                        'question' => "Do you spend time in nature/out in the sun",
                        'answers' => ["Quite a bit", "Sometimes", "Almost never"]
                    ],
                    [
                        'question' => "How often do you exercise?",
                        'answers' => ["Daily", "Weekly", "Never"]
                    ],
                    [
                        'question' => "Have you ever taken a hormone or micronutrient test?",
                        'answers' => ["Yes", "No", "I don't remember"]
                    ],
                ]
            ],
            [
                'name' => "General Health",
                'slug' => "general",
                'image_url' => "condition_general",
                'description' => "As licensed doctors, Naturopathic Doctors are able to provide a variety of services to treat a multitude of conditions. Every patient will get a personalized treatment protocol, which will most effectively address their needs and individual concerns. We may utilize specialty lab testing, dietary and lifestyle advice, nutritional and herbal remedies, and a patient centered approach. It would be our pleasure to assist you on your path to optimal wellness.",
                'questions' => [
                    [
                        'question' => "Are you familiar with what a Naturopathic Doctor is?",
                        'answers' => ["Yes, I have used one", "Yes, but I've never used one", "No"]
                    ],
                    [
                        'question' => "How long have you been suffering with your medical issues?",
                        'answers' => ["Very recently", "Under a year", "A year or more"]
                    ],
                    [
                        'question' => "How many doctors have you seen about your specific issue?",
                        'answers' => ["None", "One", "More than one"]
                    ],
                    [
                        'question' => "How many prescription medications are you currently taking?",
                        'answers' => ["None", "One to three", "Four or more"]
                    ],
                    [
                        'question' => "What are you primary wellness goals?",
                        'answers' => ["Prevention", "Feel great", "Self education"]
                    ],
                ]
            ]
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
