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
                        <p class="subtitle is-5">Optimize your health with a holistic, integrative and personalized approach to medicine. Harvey provides video consultations with naturopathic doctors, advanced lab testing and natural treatment plans — right from your home.</p>
                        <div class="button-wrapper">
                            <a href="#get-started" class="button is-primary is-medium has-arrow">Get Started</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container has-text-centered">
            <h2 class="title is-3">Failed by the medical system?</h2>
            <p class="copy-has-max-width subtitle is-4-desktop is-5-mobile">By taking a more personalized and whole-body approach to medicine, and emphasizing nutrition, lifestyle and environmental factors, Harvey can help you find the root cause of your health issues and prevent risk of disease.</p>
            <div class="button-wrapper">
                <a href="/about" class="button is-secondary is-outlined is-medium has-arrow">About Harvey</a>
            </div>
        </div>
    </section>

    <section class="section how-it-works">
        <div class="container has-text-centered">
            <h2 class="title is-4 section-header"><span>What We Treat</span></h2>
            <div class="columns">
                <div class="column">
                    <figure class="icon-wrapper icon-wrapper-has-background is-lime">
                        <img class="icon full" src="https://d35oe889gdmcln.cloudfront.net/assets/images/home/conditions/skin.png"></span>
                    </figure>
                    <div class="is-padding-top">
                        <p class="title instructions is-5"><strong>Skin Conditions</strong></p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        <a href="/conditions/skin-conditions">Learn More</a>
                    </div>
                </div>
                <div class="column">
                    <figure class="icon-wrapper icon-wrapper-has-background is-pink">
                        <img class="icon full" src="https://d35oe889gdmcln.cloudfront.net/assets/images/home/conditions/allergies.png"></span>
                    </figure>
                    <div class="is-padding-top">
                        <p class="title instructions is-5"><strong>Food Allergies</strong></p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        <a href="/conditions/food-allergies">Learn More</a>
                    </div>
                </div>
                <div class="column">
                    <figure class="icon-wrapper icon-wrapper-has-background is-brown">
                        <img class="icon full" src="https://d35oe889gdmcln.cloudfront.net/assets/images/home/conditions/stress.png"></span>
                    </figure>
                    <div class="is-padding-top">
                        <p class="title instructions is-5"><strong>Stress/Anxiety</strong></p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        <a href="/conditions/skin-anxiety">Learn More</a>
                    </div>
                </div>
                <div class="column">
                    <figure class="icon-wrapper icon-wrapper-has-background is-purple">
                        <img class="icon full" src="https://d35oe889gdmcln.cloudfront.net/assets/images/home/conditions/digestion.png"></span>
                    </figure>
                    <div class="is-padding-top">
                        <p class="title instructions is-5"><strong>Digestion Issues</strong></p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        <a href="/conditions/digestion">Learn More</a>
                    </div>
                </div>
            </div>
            <div class="columns">
                <div class="column">
                    <figure class="icon-wrapper icon-wrapper-has-background is-turquoise">
                        <img class="icon full" src="https://d35oe889gdmcln.cloudfront.net/assets/images/home/conditions/fatigue.png"></span>
                    </figure>
                    <div class="is-padding-top">
                        <p class="title instructions is-5"><strong>Fatigue</strong></p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        <a href="/conditions/fatigue">Learn More</a>
                    </div>
                </div>
                <div class="column">
                    <figure class="icon-wrapper icon-wrapper-has-background is-slategrey">
                        <img class="icon full" src="https://d35oe889gdmcln.cloudfront.net/assets/images/home/conditions/weight-loss.png"></span>
                    </figure>
                    <div class="is-padding-top">
                        <p class="title instructions is-5"><strong>Weight Loss/Gain</strong></p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        <a href="/conditions/weight-loss-gain">Learn More</a>
                    </div>
                </div>
                <div class="column">
                    <figure class="icon-wrapper icon-wrapper-has-background is-green">
                        <img class="icon full" src="https://d35oe889gdmcln.cloudfront.net/assets/images/home/conditions/womens-health.png"></span>
                    </figure>
                    <div class="is-padding-top">
                        <p class="title instructions is-5"><strong>Women's Health</strong></p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        <a href="/conditions/womens-health">Learn More</a>
                    </div>
                </div>
                <div class="column">
                    <figure class="icon-wrapper icon-wrapper-has-background is-ford">
                        <img class="icon full" src="https://d35oe889gdmcln.cloudfront.net/assets/images/home/conditions/general.png"></span>
                    </figure>
                    <div class="is-padding-top">
                        <p class="title instructions is-5"><strong>General/Other</strong></p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        <a href="/conditions/general">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="tests">
        <div class="container">
            <h2 class="title is-4 section-header"><span>Lab Testing</span></h2>
            <p class="copy-has-max-width subtitle is-4-desktop is-5-tablet is-5-mobile has-text-centered">Gain access to specialty lab tests you can't find with primary care doctors.</p>
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
        <section class="section" id="email-capture">
            <div class="container">
                <div class="has-text-centered">
                    <h2 class="copy-has-max-width title has-text-centered">
                        <div id="ebook-wrapper">
                            <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/home/ebook.png">
                        </div> Download the Harvey eBook</h2>
                    <p class="copy-has-max-width subtitle">Provide us with your email to receive our exclusive Harvey eBook <em>"10 Best Things for Your Health"</em> so you can start feeling better than ever.</p>
                    <form>
                        <input type="text" name="_gotcha" style="display: none">
                        <input type="email" name="email" v-model="guestEmail" placeholder="Personal Email" :disabled="emailCaptureSuccess">
                        <button type="submit" class="button is-primary" @click.prevent="onEmailCaptureSubmit" :disabled="emailCaptureSuccess">Send Now</button>
                        <div v-if="!emailCaptureSuccess" :class="emailCaptureClasses" v-text="emailCaptureError"></div>
                        <div v-if="emailCaptureSuccess" class="success-text">Success! Check your email to download.</div>
                    </form>
                </div>
            </div>
        </section>
    </section>

    <section class="section" id="how-it-works">
        <div class="container has-text-centered">
            <h2 class="title is-4 section-header"><span>How it Works</span></h2>
            <div class="columns">
                <div class="column">
                    <figure class="icon-wrapper icon-wrapper-has-background is-turquoise">
                        <span class="icon icon_steps_1"></span>
                    </figure>
                    <div class="is-padding-top">
                        <p class="title instructions is-5"><strong>1. Video Consultation</strong></p>
                        <p>Review your health history during a 1-hour video consultation with a naturopathic doctor.</p>
                    </div>
                </div>
                <div class="column">
                    <figure class="icon-wrapper icon-wrapper-has-background is-pink">
                        <span class="icon icon_steps_2"></span>
                    </figure>
                    <div class="is-padding-top">
                        <p class="title instructions is-5"><strong>2. Lab Testing</strong></p>
                        <p>Receive a custom lab kit mailed to your home for sample collection.</p>
                    </div>
                </div>
                <div class="column">
                    <figure class="icon-wrapper icon-wrapper-has-background is-slategrey">
                        <span class="icon icon_steps_3"></span>
                    </figure>
                    <div class="is-padding-top">
                        <p class="title instructions is-5"><strong>3. Analysis</strong></p>
                        <p>Review your test results with your doctor to gain actionable health insights.</p>
                    </div>
                </div>
                <div class="column">
                    <figure class="icon-wrapper icon-wrapper-has-background is-green">
                        <span class="icon icon_steps_4"></span>
                    </figure>
                    <div class="is-padding-top">
                        <p class="title instructions is-5"><strong>4. Treatment Plan</strong></p>
                        <p>Your doctor develops a personalized plan addressing your unique diagnosis.</p>
                    </div>
                </div>
            </div>
        </div>
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
                        <p class="is-6 is-margin is-marginless-left">While most clinics mark up the prices of their lab tests, we sell them at close to wholesale cost. <a href="lab-tests" alt="Lab Tests">Lab tests</a> start at $99 and are comparable to the out-of-pocket co-pays and deductibles you would pay at conventional medical clinics.</p>
                        <h2 class="title is-4 is-marginless-bottom"><strong>How long are consultations?</strong></h2>
                        <p class="is-6 is-margin is-marginless-left">The initial consultation is 60 minutes ($150). However, follow-up appointments could be as low as 30 minutes ($75), depending on the number of lab tests and your individual needs.</p>
                    </div>
                </div>
                <div class="column is-6 is-auto-tablet">
                    <figure>
                        <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/home/pricing.png" alt="">
                    </figure>
                </div>
            </div>
        </div>
    </section>

    <div class="anchor" id="conditions"></div>

    <section class="section how-it-works" id="get-started">
        <div class="container">
            <div class="has-text-centered has-max-width-lg">
                <h2 class="title is-3">Start your health journey.</h2>
                <p class="copy-has-max-width subtitle is-4-desktop is-5-mobile is-padding-top">Please select your most concerning health issue out of the list below, and we will share with you some articles, case studies and lab test information.</p>
                <div class="columns is-margin-top">
                    <div class="column">
                        <a href="/conditions/skin-conditions#">
                            <figure class="icon-wrapper icon-wrapper-has-background is-lime">
                                <img class="icon full" src="https://d35oe889gdmcln.cloudfront.net/assets/images/home/conditions/skin.png"></span>
                            </figure>
                            <div class="is-padding-top">
                                <p class="title instructions is-5"><strong>Skin Conditions</strong></p>
                            </div>
                        </a>
                    </div>
                    <div class="column">
                        <a href="/conditions/food-allergies#">
                            <figure class="icon-wrapper icon-wrapper-has-background is-pink">
                                <img class="icon full" src="https://d35oe889gdmcln.cloudfront.net/assets/images/home/conditions/allergies.png"></span>
                            </figure>
                            <div class="is-padding-top">
                                <p class="title instructions is-5"><strong>Food Allergies</strong></p>
                            </div>
                        </a>
                    </div>
                    <div class="column">
                        <a href="/conditions/stress-and-anxiety#">
                            <figure class="icon-wrapper icon-wrapper-has-background is-brown">
                                <img class="icon full" src="https://d35oe889gdmcln.cloudfront.net/assets/images/home/conditions/stress.png"></span>
                            </figure>
                            <div class="is-padding-top">
                                <p class="title instructions is-5"><strong>Stress/Anxiety</strong></p>
                            </div>
                        </a>
                    </div>
                    <div class="column">
                        <a href="/conditions/digestion-issues#">
                            <figure class="icon-wrapper icon-wrapper-has-background is-purple">
                                <img class="icon full" src="https://d35oe889gdmcln.cloudfront.net/assets/images/home/conditions/digestion.png"></span>
                            </figure>
                            <div class="is-padding-top">
                                <p class="title instructions is-5"><strong>Digestion Issues</strong></p>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="columns">
                    <div class="column">
                        <a href="/conditions/fatigue#">
                            <figure class="icon-wrapper icon-wrapper-has-background is-turquoise">
                                <img class="icon full" src="https://d35oe889gdmcln.cloudfront.net/assets/images/home/conditions/fatigue.png"></span>
                            </figure>
                            <div class="is-padding-top">
                                <p class="title instructions is-5"><strong>Fatigue</strong></p>
                            </div>
                        </a>
                    </div>
                    <div class="column">
                        <a href="/conditions/weight-loss-gain#">
                            <figure class="icon-wrapper icon-wrapper-has-background is-slategrey">
                                <img class="icon full" src="https://d35oe889gdmcln.cloudfront.net/assets/images/home/conditions/weight-loss.png"></span>
                            </figure>
                            <div class="is-padding-top">
                                <p class="title instructions is-5"><strong>Weight Loss/Gain</strong></p>
                            </div>
                        </a>
                    </div>
                    <div class="column">
                        <a href="/conditions/womens-health#">
                            <figure class="icon-wrapper icon-wrapper-has-background is-green">
                                <img class="icon full" src="https://d35oe889gdmcln.cloudfront.net/assets/images/home/conditions/womens-health.png"></span>
                            </figure>
                            <div class="is-padding-top">
                                <p class="title instructions is-5"><strong>Women's Health</strong></p>
                            </div>
                        </a>
                    </div>
                    <div class="column">
                        <a href="/conditions/general-health#">
                            <figure class="icon-wrapper icon-wrapper-has-background is-ford">
                                <img class="icon full" src="https://d35oe889gdmcln.cloudfront.net/assets/images/home/conditions/general.png"></span>
                            </figure>
                            <div class="is-padding-top">
                                <p class="title instructions is-5"><strong>General/Other</strong></p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
