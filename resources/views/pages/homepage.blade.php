@extends('_layouts.public')
@section('page_title','Welcome to Harvey')
@section('body_class','home')

@section('content')
<div class="sections"
    :class="{'is-loaded': homepageLoaded}"
>
    <section class="hero is-fullheight is-primary">
        <div class="hero-background"></div>
        <div class="hero-body">
            <div class="container">
                <div class="columns">
                    <div class="column is-7-tablet is-6-desktop">
                        <h1 class="title is-1">It’s time to think differently about your medicine.</h1>
                        <p class="subtitle">Harvey is the #1 online platform for integrative and naturopathic medicine. We emphasize prevention, self-healing and natural therapies for patients struggling with acute and chronic conditions.</p>
                        <div class="button-wrapper">
                            <a href="/signup" class="button is-primary is-medium has-arrow">Get Started</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container has-text-centered">
            <h2 class="title is-2">We take this personally.</h2>
            <p class="copy-has-max-width subtitle is-5">Consult with state-licensed naturopathic doctors, obtain highly-specialized lab tests you can't find in most clinics, and receive natural and holistic therapies — all without leaving your home.</p>
        </div>
    </section>

    <section class="section" id="naturopathic-medicine">
        <div class="container">
            <h2 class="title is-4 section-header"><span>Naturopathic Medicine</span></h2>
            <figure class="has-text-centered has-margin">
                <img src="/images/home/naturopathic-medicine-graph.png" alt="">
            </figure>
            <div class="columns is-multiline is-narrow">
                <div class="column is-half columns is-mobile">
                    <figure class="icon-wrapper icon-wrapper-has-background is-lime image">
                        <span class="icon icon_medicine_1"></span>
                    </figure>
                    <div class="column is-paddingless-top">
                        <p class="title is-5 is-marginless"><strong>Holistic approach</strong></p>
                        <p class="subtitle is-6">Naturopathic medicine emphasizes prevention and self-healing through evidence-based natural therapies.</p>
                    </div>
                </div>
                <div class="column is-half columns is-mobile">
                    <figure class="icon-wrapper icon-wrapper-has-background is-pink image">
                        <span class="icon icon_medicine_2"></span>
                    </figure>
                    <div class="column is-paddingless-top">
                        <p class="title is-5 is-marginless"><strong>Real medical school</strong></p>
                        <p class="subtitle is-6">100% of our Naturopathic Doctors (NDs) graduated from an accredited four-year residential naturopathic medical school.</p>
                    </div>
                </div>
                <div class="column is-half columns is-mobile">
                    <figure class="icon-wrapper icon-wrapper-has-background is-brown image">
                        <span class="icon icon_medicine_3"></span>
                    </figure>
                    <div class="column is-paddingless-top">
                        <p class="title is-5 is-marginless"><strong>State medical license</strong></p>
                        <p class="subtitle is-6">100% of our NDs must pass an extensive board examination and received a medical license from their practicing state.</p>
                    </div>
                </div>
                <div class="column is-half columns is-mobile">
                    <figure class="icon-wrapper icon-wrapper-has-background is-purple image">
                        <span class="icon icon_medicine_4"></span>
                    </figure>
                    <div class="column is-paddingless-top">
                        <p class="title is-5 is-marginless"><strong>Backed by science</strong></p>
                        <p class="subtitle is-6">We will examine your biomarkers to help you explore the root causes of your chronic issues, instead of just treating the symptoms.</p>
                    </div>
                </div>
                <div class="column is-half columns is-mobile">
                    <figure class="icon-wrapper icon-wrapper-has-background is-green image">
                        <span class="icon icon_medicine_5"></span>
                    </figure>
                    <div class="column is-paddingless-top">
                        <p class="title is-5 is-marginless"><strong>All-natural remedies</strong></p>
                        <p class="subtitle is-6">We design unique treatment plans that may include diet, nutrition, vitamins, supplements or lifestyle changes.</p>
                    </div>
                </div>
                <div class="column is-half columns is-mobile">
                    <figure class="icon-wrapper icon-wrapper-has-background is-slategrey image">
                        <span class="icon icon_medicine_6"></span>
                    </figure>
                    <div class="column is-paddingless-top">
                        <p class="title is-5 is-marginless"><strong>New primary care</strong></p>
                        <p class="subtitle is-6">Our goal is to provide you with alternative forms of therapy to complement, but not replace, traditional medical practice.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section is-paddingless-mobile">
        <div class="container">
            <div class="columns is-desktop">
                <div class="column">
                    <figure class="image image-has-max-height">
                        <picture>
                            <source media="(max-width: 999px)" srcset="/images/home/background_2_sm.jpg">
                            <source media="(min-width: 1000px)" srcset="/images/home/background_2_md.jpg">
                            <img class="hero-thumbnail" src="/images/home/background_2_md.jpg" alt="Harvey">
                        </picture>
                    </figure>
                </div>
                <div class="column has-content-vertical-aligned">
                    <div class="content has-padding-left has-padding-right">
                        <h2 class="title is-4 is-3-widescreen"><strong>Stimulate your body’s natural healing abilities to combat the underlying causes of chronic symptoms.</strong></h2>
                        <p>Far too often, people struggling to suppress chronic symptoms with prescription drugs or over-the-counter medications have been misdiagnosed by medical doctors.</p>
                        <p>Naturopathic Doctors are far more experienced in performing and interpreting specialized, non-traditional laboratory tests that can help identify nutritional deficiencies, allergies, digestive problems, imbalances in biomarkers (such as vitamins, minerals and hormones), or test for high levels of toxic metals and chemicals.</p>
                        <p>Ultimately, many chronic symptoms and diseases can be suppressed (or eliminated entirely) through very basic natural treatments — like increasing Vitamin D intake or eating omega 3 fish oil.</p>
                        <div class="button-wrapper">
                            <a href="/signup" class="button is-primary is-medium has-arrow">Get Started</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="tests">
        <div class="container">
            <h2 class="title is-4 section-header"><span>Types of Lab Tests</span></h2>
            <div class="columns is-narrow">
                <div class="column has-content-vertical-aligned">
                    <div class="content">
                        <h2 class="title is-4"><strong>Micronutrient Test</strong></h2>
                        <p>Micronutrient blood tests measure your body's ability to absorb into your white blood cells 35 different nutritional components — including vitamins, minerals, antioxidants, amino acids, fatty acids and other essential nutrients.</p>
                        <p>Scientific evidence shows us that analyzing the white blood cells gives us the most accurate analysis of a body's deficiencies that are often related to functional disorders.</p>
                    </div>
                </div>
                <div class="column has-text-right">
                    <figure>
                        <img src="/images/home/test_1.png" alt="">
                    </figure>
                </div>
            </div>
            <hr>
            <div class="columns is-narrow">
                <div class="column">
                    <figure>
                        <img src="/images/home/test_2.png" alt="">
                    </figure>
                </div>
                <div class="column has-content-vertical-aligned">
                    <div class="content">
                        <h2 class="title is-4"><strong>Food Allergy Test</strong></h2>
                        <p>Individuals with neurological, gastrointestinal and movement disorders often suffer from food allergies. Many people continue to eat these offending foods unknowingly for years, unaware of their potential effects.</p>
                        <p>Our food allergy test helps identify even the most hypersensitive of allergies caused by over 93 different foods across multiple food groups, including dairy, fruit, fish, meat, nuts, seeds, grains, beans and vegetables.</p>
                    </div>
                </div>
            </div>
            <hr>
            <div class="columns is-narrow">
                <div class="column has-content-vertical-aligned">
                    <div class="content">
                        <h2 class="title is-4"><strong>Toxic Metals Test</strong></h2>
                        <p>Our toxic metals test is the best way to identify the presence and levels of 16 toxic heavy metals (such as mercury, lead, uranium and beryllium) that have been deposited into our body tissues through the foods you eat.</p>
                        <p>Although death from metal poisoning is rare, low-level exposure to toxic metals over long periods of time may result in significant adverse health effects and chronic disease. After taking the test, your doctor will help you build a detailed detoxification and prevention plan.</p>
                    </div>
                </div>
                <div class="column has-text-right">
                    <figure>
                        <img src="/images/home/test_4.jpg" alt="">
                    </figure>
                </div>
            </div>
            <hr>
            <div class="columns is-narrow">
                <div class="column">
                    <figure>
                        <img src="/images/home/test_3.jpg" alt="">
                    </figure>
                </div>
                <div class="column has-content-vertical-aligned">
                    <div class="content">
                        <h2 class="title is-4"><strong>Toxic Chemicals Test</strong></h2>
                        <p>Every day, you and your family are at risk of exposure to toxic chemicals through pharmaceutical drugs, pesticides, packaged foods, household cleaners and environmental pollution in our air and water. Exposure to environmental pollutants has accelerated the rate of chronic illnesses like cancer, heart disease, chronic fatigue, autism, Parkinson's and Alzheimer's disease.</p>
                        <p>With this test, we can test the presence of 172 different toxic chemicals (such as benzene, xylene, vinyl chloride, pyrethroid insecticides or diphenyl phosphate) in your body and develop a treatment plan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="pricing">
        <div class="container">
            <div class="columns is-desktop">
                <figure class="image">
                    <img src="/images/home/pricing.jpg" alt="">
                </figure>
                <div class="column is-5-desktop is-offset-1-desktop has-content-vertical-aligned">
                    <div class="content">
                        <h2 class="title is-3"><strong>Pricing that makes sense</strong></h2>
                        <p class="subtitle is-6">We charge <strong>$149</strong> for a 1-hour consultation with a doctor, and our specialized lab tests range from $29 to $299 depending on complexity. We cover all costs for mailing lab kits and performing in-home blood draws.</p>
                        <p><em>Insurance typically does not cover preventative blood tests.</em></p>
                        <div class="button-wrapper">
                            <a href="/lab-tests" class="button is-primary is-medium has-arrow">Explore Other Tests</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="columns">
                <div class="column is-6-desktop is-offset-1-desktop">
                    <blockquote class="column">"We have so much confidence in our naturopathic doctors, if you are unhappy in any way, we will issue a refund for 100% of your first consultation."</blockquote>
                </div>
                <div class="column is-2-desktop is-offset-1-desktop is-hidden-mobile">
                    <img class="" src="/images/home/blockquote.jpg" alt="">
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="how-it-works">
        <div class="container">
            <h2 class="title is-4 section-header"><span>How it Works</span></h2>
            <div class="columns is-multiline">
                <div class="column columns is-mobile is-half-tablet is-auto-desktop">
                    <figure class="icon-wrapper icon-wrapper-has-background is-pink image">
                        <span class="icon icon_steps_1"></span>
                    </figure>
                    <div class="column">
                        <p class="title is-5"><strong>1. Consultation</strong></p>
                        <p>One of our state-licensed naturopathic doctors will learn everything they can about you over an hour-long phone call.</p>
                    </div>
                </div>
                <div class="column columns is-mobile is-half-tablet is-auto-desktop">
                    <figure class="icon-wrapper icon-wrapper-has-background is-turquoise image">
                        <span class="icon icon_steps_2"></span>
                    </figure>
                    <div class="column">
                        <p class="title is-5"><strong>2. Lab Testing</strong></p>
                        <p>We will send a phlebotomist to your home to take a blood, urine or stool sample for one of our highly specialized lab tests.</p>
                    </div>
                </div>
                <div class="column columns is-mobile is-half-tablet is-auto-desktop">
                    <figure class="icon-wrapper icon-wrapper-has-background is-slategrey image">
                        <span class="icon icon_steps_3"></span>
                    </figure>
                    <div class="column">
                        <p class="title is-5"><strong>3. Analysis</strong></p>
                        <p>Once the results are in, you can review every biomarker with your doctor and begin to identify the root causes of your symptoms.</p>
                    </div>
                </div>
                <div class="column columns is-mobile is-half-tablet is-auto-desktop">
                    <figure class="icon-wrapper icon-wrapper-has-background is-green image">
                        <span class="icon icon_steps_4"></span>
                    </figure>
                    <div class="column">
                        <p class="title is-5"><strong>4. Treatment</strong></p>
                        <p>Your doctor will create for you a treatment plan and may prescribe vitamins, supplements, and/or diet and lifestyle changes.</p>
                    </div>
                </div>
            </div>
            <div class="button-wrapper has-text-centered">
                <a href="/signup" class="button is-primary is-medium has-arrow">Get Started</a>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <h2 class="title is-4 section-header"><span>Learn the Facts</span></h2>
            <vertical-tabs>
                <vertical-tab class="content" label="Preventable deaths">
                    <!-- <img class="is-pulled-right" src="/images/facts/leading_cause_of_death.jpg" alt=""> -->
                    <h3 class="title is-4"><strong>Preventable deaths</strong></h3>
                    <p>At least a third of deaths each year in the U.S. <a href="https://www.cdc.gov/media/releases/2014/p0501-preventable-deaths.html" alt="Center for Disease Control">could have been prevented</a> with better nutrition and lifestyle changes. An estimated 9 out of every 10 Americans are considered malnourished — with significant vitamin or mineral deficiencies or toxic metals and chemicals in their blood. It’s no surprise that 50% of people who suffer from heart attacks had normal cholesterol numbers, and the root cause was some other nutritional imbalance that was overlooked.</p>
                    <p>Naturopathic and integrative medicine play a critical role in properly identifying nutritional deficiencies and other root causes of symptoms that can eventually lead to more serious conditions such as heart disease, cancer, stroke, respiratory disease (COPD) and diabetes (responsible for over 50% of deaths in the U.S. each year).</p>
                </vertical-tab>
                <vertical-tab class="content" label="Doctors and nutrition">
                    <!-- <img class="is-pulled-right" src="/images/facts/doctors_nutrition.jpg" alt=""> -->
                    <h3 class="title is-4"><strong>Doctors and nutrition</strong></h3>
                    <p>When was the last time you talked to your doctor for longer than 5 minutes about your diet and nutrition? It's probably been awhile. The reason is that medical doctors get less than 24 hours of coursework related to nutrition in medical school, and they <a href="https://www.ncbi.nlm.nih.gov/pmc/articles/PMC2779722/" alt="US National Library of Medicine National Institutes of Health">will admit themselves</a> that they are not properly trained to talk to their patients about nutrition.</p>
                    <p>Lab tests play an important role in your health journey. These tests give doctors valuable insights into what’s happening in your body. Unfortunately, it’s rare for medical doctors to recommend anything other than a handful of basic lab tests, mainly because they lack the specialized training necessary to interpret the lab results and they lack knowledge about diet nutrition to help you troubleshoot health issues through a naturopathic lens.</p>
                    <p>Additionally, medical doctors will typically use a population of sick people as the benchmark when determining if their patients are healthy and properly nourished, instead of optimizing for perfect health.</p>
                </vertical-tab>
                <vertical-tab class="content" label="Specialized lab tests">
                    <!-- <img class="is-pulled-right" src="/images/facts/specialized_lab_testing.jpg" alt=""> -->
                    <h3 class="title is-4"><strong>Specialized lab tests</strong></h3>
                    <p>Your body has thousands of biological markers. A large majority of chronic symptoms can be linked to imbalances in these biomarkers, such as vitamins, minerals, amino acids, toxic metals and chemicals, allergies, digestive chemicals and hormone imbalances.</p>
                    <p>These imbalances can be easily identified using a combination of conventional and specialized laboratory tests that can help your doctor evaluate functional disorders that may have been missed or misdiagnosed by medical doctors.</p>
                    <p>Because these tests are not offered by the giant lab testing companies like Quest or LabCorp who serve 90% of health systems, they are not typically recommended to patients. These tests are one of the most important tools utilized by integrative physicians to help combat preventable diseases.</p>
                </vertical-tab>
                <vertical-tab class="content" label="Clinical evidence">
                    <!-- <img class="is-pulled-right" src="/images/facts/clinical_evidence.jpg" alt=""> -->
                    <h3 class="title is-4"><strong>Clinical evidence</strong></h3>
                    <p>The concept of holistic wellness — empowering the body’s self-healing mechanisms through natural therapies — has been around for thousands of years. But now naturopathic remedies are being supported by clinical studies, and being published in real medical journals. And as more trained medical professionals turn to the practice of integrative medicine, it's becoming easier for patients to separate the real from the fake.</p>
                    <p>Below are some of the thousands of studies that prove, through actuarial studies, the profound benefits of naturopathic medicine:</p>
                    <ul>
                        <li><a href="http://www.cochrane.org/CD010037/HTN_extra-calcium-to-prevent-high-blood-pressure" alt="Calcium study">Calcium</a> can reduce blood pressure.</li>
                        <li><a href="http://circ.ahajournals.org/content/129/6/643.long" alt="Coffee study">Coffee</a> can reduce chances of heart disease.</li>
                        <li><a href="https://www.ncbi.nlm.nih.gov/pubmed/9170894" alt="Zinc study">Zinc</a> can reduce the duration of the common cold.</li>
                        <li><a href="https://www.ncbi.nlm.nih.gov/pubmed/19437058" alt="Coconut oil study">Coconut Oil</a> can help fight obesity and reduce waist circumference.</li>
                        <li><a href="http://www.sciencedirect.com/science/article/pii/S0091305715000945" alt="Tyrosine study">Tyrosine</a> can improve memory.</li>
                        <li><a href="http://jamanetwork.com/journals/jama/fullarticle/1151505" alt="Probiotics study">Probiotics</a> can prevent diarrhea.</li>
                        <li><a href="http://ebmh.bmj.com/content/12/3/78.full" alt="St. John's Wort study">St. John's Wort</a> can be effective in treating depression.</li>
                        <li><a href="http://www.sciencedirect.com/science/article/pii/S1568997213000402" alt="Vitamin D study">Vitamin D</a> has been shown to prevent everything from infectious diseases, autoimmune diseases, cardiovascular diseases, type 1 and type 2 diabetes, influenza, several types of cancer, mental illness and infertility.</li>
                    </ul>
                    <p>These medical studies are fundamentally changing the world’s perception about complementary and alternative therapies — for the better. As proof, <a href="http://www.webmd.com/diet/features/vitamins-supplements-test-your-knowledge" alt="WebMD">72% of medical doctors</a> now turn to various forms of naturopathic treatments themselves.</p>
                </vertical-tab>
                <vertical-tab class="content" label="Naturopathic doctors">
                    <!-- <img class="is-pulled-right" src="/images/facts/naturopathic_doctors.jpg" alt=""> -->
                    <h3 class="title is-4"><strong>Naturopathic doctors</strong></h3>
                    <p>Different from "naturopaths", Naturopathic Doctors (NDs) graduated from an accredited four-year residential naturopathic medical school and passed an extensive board examination. They must receive a license from their practicing state and complete 60 hours of continuing education every 2 years to maintain their license.</p>
                    <p>NDs take a highly personalized approach to medicine and treat all forms of health concerns — ranging from pediatric to geriatric, irritating symptoms to chronic illness, and physical to psychological. They emphasize prevention and self-healing through the use of evidence-based natural therapies.</p>
                    <p>They typically believe in five core philosophies:</p>
                    <ul>
                        <li>An emphasis on disease prevention</li>
                        <li>An individualized approach to medicine</li>
                        <li>Identifying the root cause of conditions</li>
                        <li>Boosting the immune system and self-healing powers</li>
                        <li>Nutritional and holistic treatments</li>
                    </ul>
                    <p>As a result, most NDs are highly trained in diet, nutrition, vitamins, supplements, homeopathic and botanical medicine, and many other forms of complementary and alternative therapies.</p>

                </vertical-tab>
                <vertical-tab class="content" label="Dangers of multivitamins">
                    <!-- <img class="is-pulled-right" src="/images/facts/identifying_quackery.jpg" alt=""> -->
                    <h3 class="title is-4"><strong>Dangers of multivitamins</strong></h3>
                    <p>Eating one multivitamin and thinking you're healthy is like reading one book and thinking you're smart. Contrary to popular belief, one size does not fit all when it comes to vitamins and supplements. There is not one universal health treatment that is best for everyone, nor is there a single diet or nutritional plan that works for everyone.</p>
                    <p>Therefore, the daily multivitamin you are taking is probably a waste of money. It's a ridiculous assumption that all men, women or children can take the same single pill and optimize for health. What if you’re a high-endurance athlete? Or a diabetic suffering from an autoimmune disease? What if you’re pregnant?</p>
                    <p>Our bodies are extremely unique and complex, with thousands of biomarkers and interlocking parts. That's why we individualized recommendations.</p>
                </vertical-tab>
                <vertical-tab class="content" label="Tainted vitamins">
                    <!-- <img class="is-pulled-right" src="/images/facts/identifying_quackery.jpg" alt=""> -->
                    <h3 class="title is-4"><strong>Tainted vitamins and supplements</strong></h3>
                    <p>People like making their own decisions about treatments. The problem is, most people aren’t very good at being their own doctor.</p>
                    <p>There are thousands of vitamin and supplement products on the market. Virtually <a href="http://www.health.harvard.edu/blog/fda-needs-stronger-rules-to-ensure-the-safety-of-dietary-supplements-201202024182" alt="Harvard article">unregulated by the FDA</a>, most dietary supplements are tainted with GMOs, steroids, synthetic fillers, colorants, contaminants and other toxic ingredients. But that doesn’t stop people. Over 50% of Americans takes at least one vitamin or supplement every day, spending $35 billion a year. And 80% of them are taken without any recommendation from a medical professional.</p>
                    <p>Vitamins can be very dangerous if taken inappropriately. As high as 60% of people in the United States are deficient in Vitamin D (one of the most important vitamins), but eating too much Vitamin D has been proven to cause dehydration, irritability, muscle weakness, fatigue and in some cases, death.</p>
                    <p>Maybe it's time we stop letting celebrities give us health and nutrition advice, and start listening to the real experts who use diagnostic lab tests to make highly-personalized and evidence-based recommendations.</p>
                </vertical-tab>
            </vertical-tabs>
        </div>
    </section>

    <section class="section" id="symptoms">
        <div class="section-wrapper">
            <div class="container content">
                <h2 class="title is-4 section-header"><span>Your symptoms</span></h2>
                <p class="copy-has-max-width subtitle is-5">The majority of chronic symptoms can be linked to imbalances in your biomarkers. To get started, tell us the severity of each of your symptoms using the sliders below (5 being the most severe).</p>
                <div class="symptoms-container">
                    <symptoms :stats="symptomsStats" @changed="onChanged"></symptoms>
                </div>
                <div class="has-text-centered">
                    <p class="disclaimer">Your selections above will be saved and shared with your doctor before your first consultation.</p>
                    <button class="button is-primary is-medium has-arrow" @click="getStarted" :disabled="symptomsSaving">
                        <span class="icon" v-if="symptomsSaving">
                            <i class="fa fa-spinner fa-spin"></i>
                        </span>
                        <span>Get Started</span>
                    </button>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
