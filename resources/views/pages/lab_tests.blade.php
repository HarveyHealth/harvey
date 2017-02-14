@extends('_layouts.public')
@section('page_title','Lab Tests & Pricing')

@section('content')
<section class="hero">
    <div class="hero-body container">
        <header class="content has-text-centered">
            <h1 class="title is-3 page-title">Lab Tests &amp; Pricing</h1>
            <p class="copy-has-max-width subtitle is-5 ">Our Naturopathic Doctors rely heavily on specialized, evidence-based clinical laboratory tests to help validate and enhance the credibility of their proposed treatments.</p>
        </header>
    </div>
</section>
<section class="section check-load"
    :class="{'is-loaded': appLoaded}"
>
    <div class="container">
        <vertical-tabs>
            <vertical-tab label="Micronutrients">
                <header class="level">
                    <div class="media-left is-pulled-left">
                        <img src="/images/lab_tests/micronutrients.png" alt="">
                    </div>
                    <div class="media-content">
                        <h3 class="title is-4"><strong>Micronutrient Test</strong></h3>
                        <p class="subtitle is-6">Sample: Blood draw</p>
                    </div>
                    <div class="media-right">
                        <p class="title is-3">$299</p>
                    </div>
                </header>
                <div class="content">
                    <blockquote>"Did you know that 9 out of 10 Americans are potassium deficient, 8 out of 10 are vitamin E deficient, 7 out of 10 are calcium deficient, 50% are deficient in vitamin A, vitamin C and magnesium, and 90% of people of color in America are deficient in vitamin D."</blockquote>
                    <p>Our micronutrient test is the most accurate and scientifically proven method of assessing vitamin, mineral, antioxidant and amino acid deficiencies.</p>
                    <p>Nutritional balance plays a key role in optimal wellness, chronic disease prevention and managing the aging process. If your chronic symptoms include fatigue, stress, depression or irritability, this test can help your doctor quantifiably measure the levels of 35 critical nutritional biomarkers.</p>
                    <div class="button-wrapper">
                        <a href="/signup" class="button is-primary is-medium has-arrow">Book Appointment</a>
                    </div>
                </div>
            </vertical-tab>
            <vertical-tab label="Hormones">
                <header class="level">
                    <div class="media-left is-pulled-left">
                        <img src="/images/lab_tests/hormones.png" alt="">
                    </div>
                    <div class="media-content">
                        <h3 class="title is-4"><strong>Hormone Test</strong></h3>
                        <p class="subtitle is-6">Sample: Blood draw</p>
                    </div>
                    <div class="media-right">
                        <p class="title is-3">$99</p>
                    </div>
                </header>
                <div class="content">
                    <blockquote>"As women enter menopausal years, their bodies' production of estrogen and other hormones needed to maintain youthful vitality rapidly declines. Continual assessment of hormone levels is necessary for women seeking to maintain a healthy hormonal balance."</blockquote>
                    <p>Our hormone panel is an important test for anyone concerned with changing hormone levels in their body as a result of age. Common symptoms of hormone imbalances include fatigue, insomnia, stress, low libido, immunity or weight imbalances.</p>
                    <p>Like nutrients, hormones influence all aspects of health and disease, including mood, sleep, metabolism, heart health and physical appearance. An imbalance of even one hormone can initiate a cascade of events that can alter additional hormones, so a comprehensive look at your hormone status is vital.</p>
                    <div class="button-wrapper">
                        <a href="/signup" class="button is-primary is-medium has-arrow">Book Appointment</a>
                    </div>
                </div>
            </vertical-tab>
            <vertical-tab label="Thyroid/Cortisol">
                <header class="level">
                    <div class="media-left is-pulled-left">
                        <img src="/images/lab_tests/thyroid.png" alt="">
                    </div>
                    <div class="media-content">
                        <h3 class="title is-4"><strong>Thyroid/Cortisol Test</strong></h3>
                        <p class="subtitle is-6">Sample: Blood draw</p>
                    </div>
                    <div class="media-right">
                        <p class="title is-3">$99</p>
                    </div>
                </header>
                <div class="content">
                    <blockquote>"Over 25 million Americans are diagnosed with a thyroid disorder each year. Thyroid disorders are four times more likely in women than men."</blockquote>
                    <p>Thyroid hormones directly regulate every cell in our body and affect our most basic functions like metabolism, emotions and thinking. The thyroid test examines several proteins that affect thyroid function and tests for specific thyroid antibodies to help your doctor detect autoimmunity (when the immune system is attacking healthy tissue in your body).</p>
                    <p>Cortisol is a stress hormone that plays a critical role in the metabolism of proteins, lipids, and carbohydrates, among other functions. This test may be necessary when someone has symptoms that suggest adrenal fatigue or Addison disease — such as weight loss, muscle weakness, fatigue, low blood pressure, abdominal pain, depression, sensitivity to cold, constipation or pale dry skin.</p>
                    <div class="button-wrapper">
                        <a href="/signup" class="button is-primary is-medium has-arrow">Book Appointment</a>
                    </div>
                </div>
            </vertical-tab>
            <vertical-tab label="CardioMetabolic">
                <header class="level">
                    <div class="media-left is-pulled-left">
                        <img src="/images/lab_tests/cardiometabolic.png" alt="">
                    </div>
                    <div class="media-content">
                        <h3 class="title is-4"><strong>CardioMetabolic Test</strong></h3>
                        <p class="subtitle is-6">Sample: Blood draw</p>
                    </div>
                    <div class="media-right">
                        <p class="title is-3">$99</p>
                    </div>
                </header>
                <div class="content">
                    <blockquote>"50% of people who have a heart attack have normal cholesterol levels. It's often the lipoproteins, not the cholesterol, that leads to artery clogging."</blockquote>
                    <p>Cardiovascular disease is the leading cause of death in the United States. Standard cholesterol tests are not sufficient in indicating your risk level (in fact, 50% of people who suffer from heart attacks had normal cholesterol levels).</p>
                    <p>Our cardiometabolic test offers a full clinical evaluation to help define your risk for atherosclerotic cardiovascular disease (ASCVD), inflammation and progression toward Type 2 Diabetes. Results of testing allow doctors to know when guidance, educational referral, or treatment is necessary.</p>
                    <p>This test is recommended for any patient who:</p>
                    <ul>
                        <li>Has a family history of heart disease or diabetes</li>
                        <li>Has been diagnosed with heart disease or diabetes</li>
                        <li>Is already taking cholesterol-lowering medications</li>
                        <li>Has been diagnosed with Metabolic Syndrome (high blood pressure)</li>
                        <li>Is significantly overweight</li>
                        <li>Has high LDL (bad cholesterol)</li>
                        <li>Has low HDL (good cholesterol)</li>
                        <li>Has high triglycerides</li>
                    </ul>
                    <div class="button-wrapper">
                        <a href="/signup" class="button is-primary is-medium has-arrow">Book Appointment</a>
                    </div>
                </div>
            </vertical-tab>
            <vertical-tab label="CBC/CMP">
                <header class="level">
                    <div class="media-left is-pulled-left">
                        <img src="/images/lab_tests/cbc-cmp.png" alt="">
                    </div>
                    <div class="media-content">
                        <h3 class="title is-4"><strong>CBC/CMP Test</strong></h3>
                        <p class="subtitle is-6">Sample: Blood draw</p>
                    </div>
                    <div class="media-right">
                        <p class="title is-3">$29</p>
                    </div>
                </header>
                <div class="content">
                    <blockquote>"The CMC/CMP test is the most common blood tests available, which can be easily added to any other specialized lab test."</blockquote>
                    <p>The CBC panel is the most common blood test available, often used to diagnose anemia, general infection, inflammation and leukemia. Your white blood cells and red blood cells are counted, along with platelets typically with an annual physical.</p>
                    <p>The CMP panel tests liver, kidney and electrolyte biomarkers to make sure your organs are in good working order. These are important tests although most physicians may only perform CMP panels during annual physicals.</p>
                    <p>The CMC/CMP panel is a great add-on for other specialized tests.</p>
                    <div class="button-wrapper">
                        <a href="/signup" class="button is-primary is-medium has-arrow">Book Appointment</a>
                    </div>
                </div>
            </vertical-tab>
            <vertical-tab label="Toxic Metals">
                <header class="level">
                    <div class="media-left is-pulled-left">
                        <img src="/images/lab_tests/toxic-metals.png" alt="">
                    </div>
                    <div class="media-content">
                        <h3 class="title is-4"><strong>Toxic Metals Test</strong></h3>
                        <p class="subtitle is-6">Sample: Urine</p>
                    </div>
                    <div class="media-right">
                        <p class="title is-3">$199</p>
                    </div>
                </header>
                <div class="content">
                    <blockquote>"Heavy metal toxicity, such as lead in your water or mercury in your fish, has become one of the most pressing health hazards in the country."</blockquote>
                    <p>High levels of toxic metals deposited in body tissues and subsequently in the brain has been known to cause significant developmental and neurological damage. Our toxic metals test is the best way to identify the presence and levels of 16 toxic heavy metals (such as mercury, lead, uranium and beryllium) that have been deposited into our body tissues through the foods you eat.</p>
                    <p>Low-level exposure to toxic metals over long periods of time can result in significant adverse health effects and chronic disease. It’s impossible to draw valid conclusions about adverse health effects of toxic metals without assessing your net retention of these metals — meaning the exposure you get from toxic metals vs what your body can properly flush out.</p>
                    <div class="button-wrapper">
                        <a href="/signup" class="button is-primary is-medium has-arrow">Book Appointment</a>
                    </div>
                </div>
            </vertical-tab>
            <vertical-tab label="Toxic Chemicals">
                <header class="level">
                    <div class="media-left is-pulled-left">
                        <img src="/images/lab_tests/toxic-chemicals.png" alt="">
                    </div>
                    <div class="media-content">
                        <h3 class="title is-4"><strong>Toxic Chemicals Test</strong></h3>
                        <p class="subtitle is-6">Sample: Urine</p>
                    </div>
                    <div class="media-right">
                        <p class="title is-3">$239</p>
                    </div>
                </header>
                <div class="content">
                    <blockquote>"There are many environmental pollutants linked to serious chronic illnesses that can be found in the air, water and products in your home."</blockquote>
                    <p>Every day, we are exposed to hundreds of toxic chemicals through products like pharmaceuticals, pesticides, packaged foods, household products, and environmental pollution. As we have become more exposed to chemical-laden products and to toxic chemicals in food, air, and water, we have been confronted with an accelerating rate of chronic illnesses.</p>
                    <p>The toxic organics chemicals test is one of the most highly-recommended tests for discovering and eliminating household toxins that are known to cancer, heart disease, chronic fatigue syndrome, chemical sensitivity, autism, ADD/ADHD, autoimmune disorders, Parkinson’s disease and Alzheimer’s disease.</p>
                    <div class="button-wrapper">
                        <a href="/signup" class="button is-primary is-medium has-arrow">Book Appointment</a>
                    </div>
                </div>
            </vertical-tab>
            <vertical-tab label="Food Allergies">
                <header class="level">
                    <div class="media-left is-pulled-left">
                        <img src="/images/lab_tests/food-allergies.png" alt="">
                    </div>
                    <div class="media-content">
                        <h3 class="title is-4"><strong>Food Allergy Test</strong></h3>
                        <p class="subtitle is-6">Sample: Finger blot (blood)</p>
                    </div>
                    <div class="media-right">
                        <p class="title is-3">$239</p>
                    </div>
                </header>
                <div class="content">
                    <blockquote>"There are eight types of food that account for majority of food allergies. We test those foods plus 85 others to find the ones you probably missed."</blockquote>
                    <p>Individuals with neurological, gastrointestinal and movement disorders often suffer from food allergies. Many people continue to eat offending foods every day for years, unaware of their potential effects. Our food allergy test helps identify even the most hypersensitive of allergies caused by over 93 different foods across multiple food groups, including dairy, fruit, fish, meat, nuts, seeds, grains, beans and vegetables.</p>
                    <p>According to numerous clinical studies, elimination of allergenic food groups can vastly improve symptoms of irritable bowel syndrome, autism, ADD/ADHD, cystic fibrosis, rheumatoid arthritis,and epilepsy. Food rotation and elimination diets can help reduce stress on the immune system, lower gut inflammation, resolve food cravings, and reduce the propensity for eating disorders.</p>
                    <div class="button-wrapper">
                        <a href="/signup" class="button is-primary is-medium has-arrow">Book Appointment</a>
                    </div>
                </div>
            </vertical-tab>
            <vertical-tab label="Microbiome (Gut)">
                <header class="level">
                    <div class="media-left is-pulled-left">
                        <img src="/images/lab_tests/microbiome.png" alt="">
                    </div>
                    <div class="media-content">
                        <h3 class="title is-4"><strong>Microbiome (Gut) Test</strong></h3>
                        <p class="subtitle is-6">Sample: Stool</p>
                    </div>
                    <div class="media-right">
                        <p class="title is-3">$199</p>
                    </div>
                </header>
                <div class="content">
                    <blockquote>"The gut is your largest endocrine organ that contains 70% of your immune system and is almost as important as your heart and brain."</blockquote>
                    <p>Our microbiome test identifies specific pathogens and other microorganisms in your gut such as yeast, parasites and bacteria that may be affecting your health. The test can measure your bacterial diversity and other useful metrics about your microbiome. It evaluates intestinal immune function, overall intestinal health, inflammation markers and levels of good bacteria in your body which may be missed by micronutrient and other tests.</p>
                    <p>Many chronic disorders, especially neurological dysfunction, come from digestive problems and inadequate nutrient absorption. Nutrients require a specific internal environment to be properly digested and transported throughout the body.</p>
                    <div class="button-wrapper">
                        <a href="/signup" class="button is-primary is-medium has-arrow">Book Appointment</a>
                    </div>
                </div>
            </vertical-tab>
            <vertical-tab label="Organic Acids">
                <header class="level">
                    <div class="media-left is-pulled-left">
                        <img src="/images/lab_tests/organic-acids.png" alt="">
                    </div>
                    <div class="media-content">
                        <h3 class="title is-4"><strong>Organic Acids Test</strong></h3>
                        <p class="subtitle is-6">Sample: Urine</p>
                    </div>
                    <div class="media-right">
                        <p class="title is-3">$299</p>
                    </div>
                </header>
                <div class="content">
                    <blockquote>"Malabsorption is a condition that prevents vitamans, proteins, sugars, fats and other food to be absorbed into the small intestine, leading to chronic digestive problems, nutritional deficiencies, yeast infections, cognitive impairment and other degenerative conditions."</blockquote>
                    <p>Our organic acids test offers a comprehensive metabolic snapshot of a your overall health with over 70 biomarkers. Specifically, it provides an accurate evaluation of intestinal yeast and bacteria. Abnormally high levels of these microorganisms can cause or worsen behavior disorders, hyperactivity, movement disorders, fatigue and immune dysfunction.</p>
                    <p>Many people with chronic illnesses and neurological disorders often excrete abnormal organic acids in their urine, which can be easily detected here.</p>
                    <div class="button-wrapper">
                        <a href="/signup" class="button is-primary is-medium has-arrow">Book Appointment</a>
                    </div>
                </div>
            </vertical-tab>
        </vertical-tabs>
    </div>
</section>
@endsection