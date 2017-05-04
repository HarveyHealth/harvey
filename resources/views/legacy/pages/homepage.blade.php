@extends('legacy._layouts.public')
@section('page_title','Welcome to Harvey')
@section('body_class','home')

@section('main_content')
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
                        <p class="subtitle">Harvey emphasizes prevention, self-healing and science-based natural therapies to help patients suffering from acute and chronic health conditions.</p>
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
            <p class="copy-has-max-width subtitle is-5">We provide virtual consultations with functional and naturopathic doctors, in-home lab testing and progressive therapies to help patients find the root causes of their health conditions instead of just treating the symptoms. This means we spend a lot of time with you!</p>
        </div>
    </section>

    <section class="section" id="integrative-medicine">
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
                        <p class="subtitle is-6">100% of our doctors must pass an extensive board examination and receive a medical license from their practicing state.</p>
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
                        <p class="subtitle is-6">We design unique treatment plans that may include diet, nutrition, supplements or lifestyle changes.</p>
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

    <section class="section" id="western-medicine">
        <div class="container">
            <div class="columns is-desktop">
                <figure class="image image-has-max-height">
                    <picture>
                        <source media="(max-width: 999px)" srcset="/images/home/shower_sm.jpg">
                        <source media="(min-width: 1000px)" srcset="/images/home/shower_md.jpg">
                        <img class="hero-thumbnail" src="/images/home/shower_md.jpg" alt="Harvey">
                    </picture>
                </figure>
                <div class="column is-5-desktop is-offset-1-desktop has-content-vertical-aligned">
                    <div class="content">
                        <h2 class="title is-4 is-3-widescreen"><strong>Stimulate your body’s natural healing abilities to combat the root underlying cause chronic conditions.</strong></h2>
                        <p>Far too often, people struggling to suppress acute or chronic symptoms with prescription drugs or over-the-counter medications have been failed by their medical doctor.</p>
                        <p>Integrative physicians tend to be more experienced in helping patients identify and treat nutritional deficiencies, allergies, digestive problems, imbalances in biomarkers (such as vitamins, minerals and hormones) and testing for high levels of toxic metals and chemicals.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="tests">
        <div class="container">
            <h2 class="title is-4 section-header"><span>Lab Testing</span></h2>
            <p class="copy-has-max-width subtitle is-5 has-text-centered">We partner with the top boutique laboratories and testing centers across the country to give you easy access to a broad inventory of lab tests — ranging from basic blood tests to more specialized panels like our allergy, hormone and microbiome tests.</p>
            <div class="columns is-narrow">
                <div class="column has-content-vertical-aligned">
                    <div class="content">
                        <h2 class="title is-4"><strong>Micronutrient Test</strong></h2>
                        <p>Micronutrient blood tests measure your body's level of absorption of 35 different nutritional components into your white blood cells — including vitamins, minerals, antioxidants, amino acids, fatty acids and other essential nutrients.</p>
                        <p>Scientific evidence shows us that analyzing the white blood cells gives us the most accurate analysis of a body's deficiencies that are often related to functional disorders.</p>
                    </div>
                </div>
                <div class="column has-text-right">
                    <figure class="circle">
                        <img src="/images/home/square-33.png" alt="">
                    </figure>
                </div>
            </div>
            <hr>
            <div class="columns is-narrow">
                <div class="column">
                    <figure class="circle">
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
                        <p>Our toxic metals test is the best way to identify the presence and levels of 16 toxic heavy metals (such as mercury, lead, uranium and beryllium) that have been deposited into our body tissues through food and environmental factors.</p>
                        <p>Low-level exposure to toxic metals over long periods of time may result in significant adverse health effects and chronic disease. After taking the test, your doctor will help you build a detailed detoxification and treatment plan to prevent future exposure.</p>
                    </div>
                </div>
                <div class="column has-text-right">
                    <figure class="circle">
                        <img src="/images/home/square-50.png" alt="">
                    </figure>
                </div>
            </div>
            <hr>
            <div class="columns is-narrow">
                <div class="column">
                    <figure class="circle">
                        <img src="/images/home/square-9.png" alt="">
                    </figure>
                </div>
                <div class="column has-content-vertical-aligned">
                    <div class="content">
                        <h2 class="title is-4"><strong>Toxic Chemicals Test</strong></h2>
                        <p>Every day, you and your family are at risk of exposure to toxic chemicals through pesticides, packaged foods, household cleaners and environmental pollution in your air and water. Exposure to environmental pollutants have been known to accelerate the rate of chronic illnesses like cancer, heart disease, chronic fatigue, autism, Parkinson's and Alzheimer's disease.</p>
                        <p>With this test, we can test the presence of 172 different toxic chemicals in your body such as benzene, xylene, vinyl chloride and diphenyl phosphate.</p>
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
                        <h2 class="title is-3"><strong>Explore our lab tests</strong></h2>
                        <p class="subtitle is-6">We charge $149 for each consultation with a doctor and lab testing ranges from $99 to $299 depending on complexity. We cover all the costs of mailing lab kits and performing in-home blood draws. <strong>Harvey services are not typically covered by medical insurance</strong>.</p>
                        <div class="button-wrapper">
                            <a href="/lab-tests" class="button is-secondary is-outlined is-medium has-arrow">Lab Tests & Pricing</a>
                        </div>
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
                        <p>We will mail lab kits to your home in a discrete package, and if necessary send a phlebotomist to your home for a blood draw.</p>
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
                        <p>Your doctor will create for you a treatment plan and may prescribe supplements, and/or diet and lifestyle changes.</p>
                    </div>
                </div>
            </div>
            <div class="button-wrapper has-text-centered">
                <a href="/signup" class="button is-primary is-medium has-arrow">Get Started</a>
            </div>
        </div>
    </section>

    <section class="section" id="symptoms">
        <div class="section-wrapper">
            <div class="container content">
                <h2 class="title is-4 section-header"><span>Where We Can Help</span></h2>
                <p class="copy-has-max-width subtitle is-5 has-text-centered">Our physicians have experience treating patients suffering from a wide range of chronic health conditions — including auto-immune diseases, diabetes, eczema, fibromyalgia, autism, migraines and much more.</p>
                <div class="columns is-multiline">
                    <div class="column columns is-mobile is-half-tablet is-auto-desktop">
                        <figure class="has-text-centered has-margin circle">
                            <img src="/images/home/square-11.png" alt="">
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
                            <label class="label">Musculoskeletal</label>
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
                            <img src="/images/home/square-52.png" alt="">
                        </figure>
                        <div class="column">
                            <label class="label">Respiratory</label>
                            <label class="symptoms-selector-description">Allergies, breathing problems, chronic cough/cold issues, bronchial inflammation, etc.</label>
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
                <p class="copy-has-max-width subtitle is-5 has-text-centered">The first step to better health is to <a href="/book" alt="">book a consultation</a> with a doctor. If you're not quite ready yet, you can join our monthly newsletter and we'll send you some hand-picked patient stories once a month.</p>

                <div id="mc_embed_signup">
                    <form action="//goharvey.us15.list-manage.com/subscribe/post?u=dc828d195bee3640b849c2838&amp;id=93440a985d" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                        <div id="mc_embed_signup_scroll">
                        <input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="Email address" required>
                        <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_dc828d195bee3640b849c2838_93440a985d" tabindex="-1" value=""></div>
                        <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
                        </div>
                    </form>
                </div>

                <div class="section" id="blog">
                    <div class="container">
                        <script src="//assets.juicer.io/embed.js" type="text/javascript"></script>
                        <link href="//assets.juicer.io/embed.css" media="all" rel="stylesheet" type="text/css" />
                        <ul class="juicer-feed" data-feed-id="goharveyapp"></ul>
                        <div class="column has-text-centered">
                            <a href="https://blog.goharvey.com" id="blog-link" alt="Harvey Blog">Visit blog for full articles</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="section-wrapper">
            <div class="container content">
                <div class="has-text-centered">
                    <h2 class="title is-3">You deserve to feel your best.</h2>
                    <div class="button-wrapper">
                        <a href="/signup" class="button is-primary is-medium has-arrow">Get Started</a>
                    </div>

                </div>
            </div>
        </div>
    </section>

</div>
@endsection
