@extends('legacy._layouts.public')
@section('page_title','Holistic and Integrative Medicine')
@section('body_class','home')
@section('main_content')

<div class="sections check-load" :class="{'is-loaded': appLoaded}">
    <section class="hero hero-background">
        <div id="hero-video-container">
            <video id="hero-video" autoplay loop muted></video>
            <div id="video-cover"></div>
            <div id="overlay"></div>
        </div>
        <div class="hero-body">
            <div class="container">
                <div class="columns">
                    <div class="column is-7 is-6-desktop">
                        <h1 class="title is-1">Choose better health.</h1>
                        <p class="subtitle is-5">Optimize your health with a holistic, integrative and personalized approach to medicine. Harvey Health provides video consultations with naturopathic doctors, advanced lab testing and natural treatment plans — right from your home.</p>
                        <div class="button-wrapper">
                            <a href="/#conditions" class="button is-primary is-medium has-arrow">Explore Conditions</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container has-text-centered">
            <h2 class="title is-3">Find the root cause of symptoms.</h2>
            <p class="copy-has-max-width subtitle is-4-desktop is-5-mobile">By taking a more personalized and whole-body approach to medicine, and emphasizing nutrition, lifestyle and environmental factors, Harvey Health can help you find the root cause of health issues and prevent risk of disease.</p>
            <div class="button-wrapper">
                <a href="/about" class="button is-secondary is-outlined is-medium has-arrow">About Harvey</a>
            </div>
        </div>
    </section>

    <section class="section how-it-works">
        <div class="container has-text-centered">
            <h2 class="title is-4 section-header"><span>How it Works</span></h2>
            <div class="columns">
                <div class="column">
                    <figure class="icon-wrapper icon-wrapper-has-background is-gray">
                        <span class="icon icon_steps_1"></span>
                    </figure>
                    <div class="is-padding-top">
                        <p class="title instructions is-5"><strong>1. Video Consultation</strong></p>
                        <p>Review your health conditions during a 1-hour video consultation with a naturopathic doctor.</p>
                    </div>
                </div>
                <div class="column">
                    <figure class="icon-wrapper icon-wrapper-has-background is-gray">
                        <span class="icon icon_steps_2"></span>
                    </figure>
                    <div class="is-padding-top">
                        <p class="title instructions is-5"><strong>2. Lab Testing</strong></p>
                        <p>Receive a custom lab kit mailed to your home for sample collection (home blood draws available).</p>
                    </div>
                </div>
                <div class="column">
                    <figure class="icon-wrapper icon-wrapper-has-background is-gray">
                        <span class="icon icon_steps_3"></span>
                    </figure>
                    <div class="is-padding-top">
                        <p class="title instructions is-5"><strong>3. Analysis</strong></p>
                        <p>Review your test results with your doctor to help diagnose and gain actionable health insights.</p>
                    </div>
                </div>
                <div class="column">
                    <figure class="icon-wrapper icon-wrapper-has-background is-gray">
                        <span class="icon icon_steps_4"></span>
                    </figure>
                    <div class="is-padding-top">
                        <p class="title instructions is-5"><strong>4. Treatment Plan</strong></p>
                        <p>Receive a natural treatment plan that may include professional-grade vitamins and supplements.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="anchor" id="conditions"></div>

    <section class="section how-it-works">
        <div class="container">
            <h2 class="title is-4 section-header"><span>What We Treat</span></h2>
            <div class="has-text-centered has-max-width-xl">
                <p class="copy-has-max-width subtitle is-4-desktop is-5-mobile">Select your most concerning health issue out of the list below to learn how our integrative doctors might approach your condition.</p>
                <div class="columns is-margin-top">
                    <div class="column" v-for="(condition, index) in conditions" v-if="index < 4">
                        <a :href="'/conditions/' + condition.slug">
                            <figure :class="'icon-wrapper icon-wrapper-has-background expand ' + State.conditionIconColors[index]">
                                <img class="icon full" :src="condition.image_url">
                            </figure>
                        </a>
                        <div class="is-padding-top">
                            <p class="title instructions is-5"><strong v-text="condition.name"></strong></p>
                            <!-- <p v-if="State.conditionSubText[index]" v-text="State.conditionSubText[index]"></p> -->
                            <a :href="'/conditions/' + condition.slug">Learn More</a>
                        </div>
                    </div>
                </div>
                <div class="columns">
                    <div class="column" v-for="(condition, index) in conditions" v-if="index > 3">
                        <a :href="'/conditions/' + condition.slug">
                            <figure :class="'icon-wrapper icon-wrapper-has-background expand ' + State.conditionIconColors[index]">
                                <img class="icon full" :src="condition.image_url">
                            </figure>
                        </a>
                        <div class="is-padding-top">
                            <p class="title instructions is-5"><strong v-text="condition.name"></strong></p>
                            <!-- <p v-if="State.conditionSubText[index]" v-text="State.conditionSubText[index]"></p> -->
                            <a :href="'/conditions/' + condition.slug">Learn More</a>
                        </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="tests">
        <div class="container">
            <h2 class="title is-4 section-header"><span>Lab Testing</span></h2>
            <p class="copy-has-max-width subtitle is-4-desktop is-5-tablet is-5-mobile has-text-centered">Gain access to specialty lab tests you can't find with conventional practitioners. All of our tests can be taken from the comfort of your home*.</p>
            <p class="small has-text-centered has-max-width-lg"><em>*In-home blood draw technicians are unavailable in some rural areas.</em></p>
            <div class="columns is-narrow">
                <div class="column has-content-vertical-aligned">
                    <div class="content">
                        <h2 class="title is-4"><strong>Micronutrient Test</strong></h2>
                        <p>Micronutrient blood tests measure your body's level of absorption of 35 different nutritional components in your white blood cells — including vitamins, minerals, antioxidants, amino acids, fatty acids and other essential nutrients.</p>
                        <p>Scientific evidence shows us that analyzing the white blood cells gives us a very accurate analysis of a body's deficiencies that are often related to functional disorders.</p>
                    </div>
                </div>
                <div class="column has-text-right">
                    <figure>
                        <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/home/micronutrient.jpg" alt="">
                    </figure>
                </div>
            </div>
            <hr>
            <div class="columns is-narrow">
                <div class="column">
                    <figure>
                        <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/home/allergy.jpg" alt="">
                    </figure>
                </div>
                <div class="column has-content-vertical-aligned">
                    <div class="content">
                        <h2 class="title is-4"><strong>Food Allergy Test</strong></h2>
                        <p>Individuals with neurological, gastrointestinal or skin disorders often suffer from food allergies. Many people continue to eat offending foods unknowingly for years, unaware of their potential effects.</p>
                        <p>Our food allergy test helps identify even the most hypersensitive of allergies caused by over 93 different foods across multiple food groups, such as dairy, fruit, fish, meat, nuts, grains and vegetables.</p>
                    </div>
                </div>
            </div>
            <hr>
            <div class="columns is-narrow">
                <div class="column has-content-vertical-aligned">
                    <div class="content">
                        <h2 class="title is-4"><strong>Hormone Test</strong></h2>
                        <p>Our hormone panel is an important test for anyone concerned with changing hormone levels in their body, often due to age. Common symptoms of hormone imbalances include fatigue, insomnia, stress, mood swings, low libido, immunity or weight imbalances.</p>
                        <p>Hormones influence all aspects of health and disease, including metabolism, heart health and physical appearance.</p>
                    </div>
                </div>
                <div class="column has-text-right">
                    <figure>
                        <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/home/hormone.jpg" alt="">
                    </figure>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="feature">
        <div class="container">
            <div class="columns is-narrow">
                <figure class="image">
                    <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/home/package-door.jpg" alt="">
                </figure>
                <div class="column is-5-desktop is-offset-1-desktop is-12-tablet has-content-vertical-aligned">
                    <div class="content">
                        <h2 class="title is-3 is-padding-top"><strong>Lab testing delivered right to your doorstep.</strong></h2>
                        <p class="is-6">All our lab tests can be taken in the comfort of your home. If recommended by your doctor, we will mail you a lab kit and (if necessary) schedule a mobile phlebotomist to perform a free in-home blood draw.</p>
                        <div class="button-wrapper">
                            <a href="/lab-tests" class="button is-secondary is-outlined is-medium has-arrow">
                                <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/home/vial.png"> Lab Tests
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section is-paddingless is-marginless" id="social-feed">
        <ul class="juicer-feed" data-feed-id="goharveyapp" data-per="15"></ul>
    </section>

    <div class="anchor" id="prices"></div>

    <section class="section" id="pricing">
        <div class="container">
            <h2 class="title is-4 section-header"><span>Simple Pricing</span></h2>
            <div class="columns is-narrow is-marginless">
                <div class="column is-6 is-offset-1-desktop has-content-vertical-aligned is-paddingless">
                    <div class="content">
                        <h2 class="title is-4 is-marginless-bottom"><strong>Do you take insurance?</strong></h2>
                        <p class="is-6 is-margin is-marginless-left">We are not contracted with any insurance providers at this time. Preventative healthcare expenditures and specialty lab tests are not typically reimbursable under most health plans. However, you may be able to use an HSA/FSA account to pay for our services.</p>
                        <h2 class="title is-4 is-marginless-bottom"><strong>How much are lab tests?</strong></h2>
                        <p class="is-6 is-margin is-marginless-left">While most clinics mark up the prices of their speciality lab tests, we sell them at wholesale cost. <a href="lab-tests" alt="Lab Tests">Lab tests</a> range from $99 to $320.</p>
                        <h2 class="title is-4 is-marginless-bottom"><strong>How long are consultations?</strong></h2>
                        <p class="is-6 is-margin is-marginless-left">The initial consultation is 60 minutes for $150. Follow-ups to review lab results are typically 30 minutes for $75.</p>
                    </div>
                </div>
                <div class="column is-6 is-auto-tablet has-text-centered">
                    <figure>
                        <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/home/pricing.png" alt="">
                    </figure>
                </div>
            </div>
        </div>
    </section>

    <section class="section get-started">
        <div class="container">
            <div class="has-text-centered has-max-width-lg">
                <h2 class="title is-3">Start your health journey.</h2>
                <p class="copy-has-max-width subtitle is-4-desktop is-5-mobile is-padding-top">Our integrative doctors are available for virtual consultations, lab testing and treatment for a wide variety of health conditions.</p>
                <div class="button-wrapper">
                    <a href="/#conditions" class="button is-primary is-medium has-arrow">Explore Conditions</a>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
