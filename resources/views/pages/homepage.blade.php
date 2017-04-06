@extends('_layouts.public')
@section('page_title','Welcome to Harvey')
@section('body_class','home')

@section('content')
<div class="sections check-load"
    :class="{'is-loaded': appLoaded}"
>
    <section class="hero is-fullheight is-primary">
        <div class="hero-background"></div>
        <div class="hero-body">
            <div class="container">
                <div class="columns">
                    <div class="column is-7-tablet is-6-desktop">
                        <h1 class="title is-1">Personalized and integrative medicine, unique as you are.</h1>
                        <p class="subtitle">Harvey physicians emphasize prevention, self-healing and evidence-based natural therapies to help people struggling with acute and chronic health conditions.</p>
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
            <h2 class="title is-2">Mind. Body. Spirit.</h2>
            <p class="copy-has-max-width subtitle is-5">We offer virtual consultations with state-licensed integrative physicians and provide access to specialized lab tests and health diagnostics — all without leaving your home.</p>
        </div>
    </section>

    <section class="section" id="naturopathic-medicine">
        <div class="container">
            <h2 class="title is-4 section-header"><span>Integrative Medicine</span></h2>
            <figure class="has-text-centered has-margin">
                <img src="/images/home/venn-diagram.png" alt="">
            </figure>
            <div class="columns is-multiline is-narrow">
                <div class="column is-half columns is-mobile">
                    <figure class="icon-wrapper icon-wrapper-has-background is-lime image">
                        <span class="icon icon_medicine_1"></span>
                    </figure>
                    <div class="column is-paddingless-top">
                        <p class="title is-5 is-marginless"><strong>Holistic approach</strong></p>
                        <p class="subtitle is-6">Integrative medicine emphasizes prevention and self-healing through evidence-based natural therapies.</p>
                    </div>
                </div>
                <div class="column is-half columns is-mobile">
                    <figure class="icon-wrapper icon-wrapper-has-background is-pink image">
                        <span class="icon icon_medicine_2"></span>
                    </figure>
                    <div class="column is-paddingless-top">
                        <p class="title is-5 is-marginless"><strong>Medical school</strong></p>
                        <p class="subtitle is-6">100% of our Naturopathic Doctors (NDs) graduated from an accredited four-year residential naturopathic medical school.</p>
                    </div>
                </div>
                <div class="column is-half columns is-mobile">
                    <figure class="icon-wrapper icon-wrapper-has-background is-brown image">
                        <span class="icon icon_medicine_3"></span>
                    </figure>
                    <div class="column is-paddingless-top">
                        <p class="title is-5 is-marginless"><strong>Medical license</strong></p>
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
                        <p class="title is-5 is-marginless"><strong>Natural remedies</strong></p>
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
                        <p>Ultimately, many chronic symptoms and diseases can be suppressed or eliminated entirely through very basic natural treatments — like increasing intake of Vitamin D or eating more iron and omega 3 fish oil.</p>
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
            <p class="copy-has-max-width subtitle is-5 has-text-centered">We partner with the top boutique laboratories and testing centers across the country to give you easy access to a broad inventory of lab tests — ranging from basic blood tests to more specialized panels like our hormone and microbiome tests.</p>
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
                        <img src="/images/home/square-33.png" alt="">
                    </figure>
                </div>
            </div>
            <hr>
            <div class="columns is-narrow">
                <div class="column">
                    <figure>
                        <img src="/images/home/square-8.png" alt="">
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
                        <p>Although death from metal poisoning is rare, low-level exposure to toxic metals over long periods of time may result in significant adverse health effects and chronic disease. After taking the test, your doctor will help you build a detailed detoxification and treatment plan to prevent future exposure.</p>
                    </div>
                </div>
                <div class="column has-text-right">
                    <figure>
                        <img src="/images/home/square-50.png" alt="">
                    </figure>
                </div>
            </div>
            <hr>
            <div class="columns is-narrow">
                <div class="column">
                    <figure>
                        <img src="/images/home/square-32.png" alt="">
                    </figure>
                </div>
                <div class="column has-content-vertical-aligned">
                    <div class="content">
                        <h2 class="title is-4"><strong>Toxic Chemicals Test</strong></h2>
                        <p>Every day, you and your family are at risk of exposure to toxic chemicals through pharmaceutical drugs, pesticides, packaged foods, household cleaners and environmental pollution in your air and water. Exposure to environmental pollutants have been known to accelerate the rate of chronic illnesses like cancer, heart disease, chronic fatigue, autism, Parkinson's and Alzheimer's disease.</p>
                        <p>With this test, we can test the presence of 172 different toxic chemicals in your body such as benzene, xylene, vinyl chloride and diphenyl phosphate.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="how-it-works">
        <div class="container">
            <h2 class="title is-4 section-header"><span>How it Works</span></h2>
            <p class="copy-has-max-width subtitle is-5">Build a healthy, cooperative relationship with an integrative physician of your choice, perform lab testing in your home, and receive results and treatment plans 100% private and online.</p>
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

    <section class="section" id="pricing">
        <div class="container">
            <div class="columns is-desktop">
                <figure class="image">
                    <img src="/images/home/pricing.jpg" alt="">
                </figure>
                <div class="column is-5-desktop is-offset-1-desktop has-content-vertical-aligned">
                    <div class="content">
                        <h2 class="title is-3"><strong>Pricing that makes sense</strong></h2>
                        <p class="subtitle is-6">We charge $149 for each consultation with a doctor and lab testing ranges from $99 to $299 depending on complexity. We cover all the costs of mailing lab kits and performing in-home blood draws. <strong>Harvey services are not typically covered by medical insurance</strong>.</p>
                        <div class="button-wrapper">
                            <a href="/lab-tests" class="button is-primary is-outlined is-medium has-arrow">Explore Other Tests</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="symptoms">
        <div class="section-wrapper">
            <div class="container content">
                <h2 class="title is-4 section-header"><span>Where We Can Help</span></h2>
                <p class="copy-has-max-width subtitle is-5 has-text-centered">Our physicians have experience treating patients suffering from a wide range of health conditions — including menopause, fatigue, auto-immune diseases, fibromyalgia, autism, hypertension, migraines, diabetes, and even toxic metal exposure.</p>
                <div class="symptoms-container">
                    <div class="columns is-multiline">
                        <div class="column columns is-mobile is-half-tablet is-auto-desktop">
                            <figure class="has-text-centered has-margin circle">
                                <img src="/images/home/square-16.png" alt="">
                            </figure>
                            <div class="column">
                                <label class="label">Cardiovascular</label>
                                <label class="symptoms-selector-description">Chest pain, dizziness, fainting, fevers, irregular heartbeat, shortness of breath, leg swelling, etc.</label>
                            </div>
                        </div>
                        <div class="column columns is-mobile is-half-tablet is-auto-desktop">
                            <figure class="has-text-centered has-margin circle">
                                <img src="/images/home/square-48.png" alt="">
                            </figure>
                            <div class="column">
                                <label class="label">Digestive</label>
                                <label class="symptoms-selector-description">Acid reflux, constipation, gas, diarrhea, heartburn, indigestion, bloating, stomach pain, cramps, etc.</label>
                            </div>
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column columns is-mobile is-half-tablet is-auto-desktop">
                            <figure class="has-text-centered has-margin circle">
                                <img src="/images/home/square-2.png" alt="">
                            </figure>
                            <div class="column">
                                <label class="label">Endocrine/Hormonal</label>
                                <label class="symptoms-selector-description">Depression, fatigue, hot flashes, insomnia, mood swings, night sweats, stress, vaginal dryness, weight gain/loss, etc.</label>
                            </div>
                        </div>
                        <div class="column columns is-mobile is-half-tablet is-auto-desktop">
                            <figure class="has-text-centered has-margin circle">
                                <img src="/images/home/square-28.png" alt="">
                            </figure>
                            <div class="column">
                                <label class="label">Dermatological</label>
                                <label class="symptoms-selector-description">Hair, skin and nails weakness, and other exocrine gland issues.</label>
                            </div>
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column columns is-mobile is-half-tablet is-auto-desktop">
                            <figure class="has-text-centered has-margin circle">
                                <img src="/images/home/square-26.png" alt="">
                            </figure>
                            <div class="column">
                                <label class="label">Immune</label>
                                <label class="symptoms-selector-description">Frequent colds, flus, cold sores, swollen lymph glands, and/or fighting a known autoimmune diseases.</label>
                            </div>
                        </div>
                        <div class="column columns is-mobile is-half-tablet is-auto-desktop">
                            <figure class="has-text-centered has-margin circle">
                                <img src="/images/home/square-20.png" alt="">
                            </figure>
                            <div class="column">
                                <label class="label">Musculo-skeletal</label>
                                <label class="symptoms-selector-description">Aches, muscle pain, body fatigue, loss of muscle control, etc.</label>
                            </div>
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column columns is-mobile is-half-tablet is-auto-desktop">
                            <figure class="has-text-centered has-margin circle">
                                <img src="/images/home/square-14.png" alt="">
                            </figure>
                            <div class="column">
                                <label class="label">Nervous</label>
                                <label class="symptoms-selector-description">Headaches, migraines, numbness, tingling, tremors, etc.</label>
                            </div>
                        </div>
                        <div class="column columns is-mobile is-half-tablet is-auto-desktop">
                            <figure class="has-text-centered has-margin circle">
                                <img src="/images/home/square-51.png" alt="">
                            </figure>
                            <div class="column">
                                <label class="label">Renal/Urinary</label>
                                <label class="symptoms-selector-description">Loss of bladder control, urinary tract infection, liver/kidney issues, etc.</label>
                            </div>
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column columns is-mobile is-half-tablet is-auto-desktop">
                            <figure class="has-text-centered has-margin circle">
                                <img src="/images/home/square-4.png" alt="">
                            </figure>
                            <div class="column">
                                <label class="label">Reproductive</label>
                                <label class="symptoms-selector-description">Impotence, loss of libido, pre/post-menopause, yeast infections, and other reproductive issues.</label>
                            </div>
                        </div>
                        <div class="column columns is-mobile is-half-tablet is-auto-desktop">
                            <figure class="has-text-centered has-margin circle">
                                <img src="/images/home/square-31.png" alt="">
                            </figure>
                            <div class="column">
                                <label class="label">Respiratory</label>
                                <label class="symptoms-selector-description">Allergies, breathing problems, chronic cough/cold issues, bronchial inflammation, etc.</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="get-started">
        <div class="section-wrapper">
            <div class="container content">
                <h2 class="title is-4 section-header"><span>Get Started</span></h2>
                <p class="copy-has-max-width subtitle is-5 has-text-centered">The first step to better health is to <a href="/book" alt="">book a consultation</a> with a doctor. If you're not quite ready yet, you can join our monthly newsletter and we'll keep in touch.</p>

                <!-- Begin MailChimp Signup Form -->
                <link href="//cdn-images.mailchimp.com/embedcode/horizontal-slim-10_7.css" rel="stylesheet" type="text/css">
                <div id="mc_embed_signup">
                    <form action="//goharvey.us15.list-manage.com/subscribe/post?u=dc828d195bee3640b849c2838&amp;id=93440a985d" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                        <div id="mc_embed_signup_scroll">
                        <input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="Email address" required>
                        <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_dc828d195bee3640b849c2838_93440a985d" tabindex="-1" value=""></div>
                        <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
                        </div>
                    </form>
                </div>
                <!--End mc_embed_signup-->

                <div class="section" id="blog">
                    <div class="container">
                        <script src="//assets.juicer.io/embed.js" type="text/javascript"></script>
                        <link href="//assets.juicer.io/embed.css" media="all" rel="stylesheet" type="text/css" />
                        <ul class="juicer-feed" data-feed-id="goharveyapp"></ul>
                        <a id="blog-link">Visit blog for full articles</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="section-wrapper">
            <div class="container content">
                <div class="has-text-centered">
                    <h2 class="title is-3">Your journey starts here.</h2>
                    <button class="button is-primary is-medium has-arrow">
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
