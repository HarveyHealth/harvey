@extends('legacy._layouts.public')
@section('page_title','Holistic and Integrative Medicine')
@section('body_class','home')
@section('main_content')

<div class="sections check-load" :class="{'is-loaded': appLoaded}">
    <section class="hero is-fullheight is-primary">
        <div class="hero-background"></div>
        <div class="hero-body">
            <div class="container">
                <div class="columns">
                    <div class="column is-6-desktop is-8-tablet">
                        <h1 class="title is-1">Choose better health.</h1>
                        <p class="subtitle is-5">Improve your healthspan with a holistic, integrative and personalized approach to medicine. Harvey offers video consultations with naturopathic doctors, advanced lab tests and natural treatment plans — without leaving your home.</p>
                        <div class="columns">
                            <div class="column is-4 is-5-tablet">
                                <a href="//www.youtube.com/watch?v=nfyk7irbYsw&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video" frameborder="0" data-lity allowfullscreen>
                                    <img src="/images/home/clinic.jpg" alt="">
                                </a>
                            </div>
                            <div class="column is-6-desktop is-8-tablet is-paddingless-left">
                                <p class="title is-5 is-marginless"><strong>Start your health journey</strong></p>
                                <p class="subtitle is-6">Learn what makes Harvey unique from other medical practices.</p>
                            </div>
                        </div>
                        <div class="button-wrapper">
                            <a href="/get-started" class="button is-primary is-medium has-arrow">Book Appointment</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container has-text-centered">
            <h2 class="title is-3">Your health is our focus.</h2>
            <p class="copy-has-max-width subtitle is-4-desktop is-5-tablet is-5-mobile">Harvey's doctors take a preventative, nutritional and relationship-driven approach to medicine to help you find the root cause of your chronic health conditions and reduce your risk of serious disease.</p>
            <div class="button-wrapper">
                <a href="#scroll-here" class="button is-secondary is-outlined is-medium has-arrow">Patient Stories</a>
            </div>
        </div>
    </section>

    <section class="section" id="how-it-works">
        <div class="container has-text-centered">
            <h2 class="title is-4 section-header"><span>How it Works</span></h2>
            <div class="columns is-multiline">
                <div class="column is-half-tablet is-auto-desktop">
                    <figure class="icon-wrapper icon-wrapper-has-background is-turquoise image">
                        <span class="icon icon_steps_1"></span>
                    </figure>
                    <div class="column is-paddingless-bottom">
                        <p class="title instructions is-5"><strong>1. Video Consultation</strong></p>
                        <p>Review your health history in a 1-hour video consultation with a naturopathic doctor.</p>
                    </div>
                </div>
                <div class="column is-half-tablet is-auto-desktop">
                    <figure class="icon-wrapper icon-wrapper-has-background is-pink image">
                        <span class="icon icon_steps_2"></span>
                    </figure>
                    <div class="column is-paddingless-bottom">
                        <p class="title instructions is-5"><strong>2. Lab Testing</strong></p>
                        <p>Receive a custom lab kit mailed to your home for sample collection.</p>
                    </div>
                </div>
                <div class="column is-half-tablet is-auto-desktop">
                    <figure class="icon-wrapper icon-wrapper-has-background is-slategrey image">
                        <span class="icon icon_steps_3"></span>
                    </figure>
                    <div class="column is-paddingless-bottom">
                        <p class="title instructions is-5"><strong>3. Analysis</strong></p>
                        <p>Review your test results with your doctor to gain actionable health insights.</p>
                    </div>
                </div>
                <div class="column is-half-tablet is-auto-desktop">
                    <figure class="icon-wrapper icon-wrapper-has-background is-green image">
                        <span class="icon icon_steps_4"></span>
                    </figure>
                    <div class="column is-paddingless-bottom">
                        <p class="title instructions is-5"><strong>4. Treatment Plan</strong></p>
                        <p>Your doctor develops a personalized plan addressing your unique diagnosis.</p>
                    </div>
                </div>
            </div>
        </div>
<!--         <div class="container is-margin-top is-margin-bottom">
            <div class="columns">
                <div class="column is-4-desktop is-offset-2-desktop is-5-tablet is-offset-1-tablet">
                    <a href="//www.youtube.com/watch?v=wAUQgwbUUYA&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video" frameborder="0" data-lity allowfullscreen>
                        <img src="/images/home/sandra.jpg" alt="">
                    </a>
                </div>
                <div class="column is-4-desktop is-6-tablet has-text-left">
                    <p class="title is-3 is-marginless-left is-margin-top"><em><strong>Hey, I'm Sandra!</strong></em></p>
                    <p>I'm one of the Operations Managers on the Harvey team. I have a passion for nutrition, holistic medicine and helping people on their journey to better health.</p>
                </div>
            </div>
        </div> -->
    </section>

    <section class="section" id="advisors">
        <div class="container">
            <h2 class="title is-4 section-header"><span>Medical Leadership</span></h2>
            <div class="columns is-narrow">
                <div class="column is-3-desktop is-half-tablet is-auto-desktop">
                    <a href="//www.youtube.com/watch?v=2bjmlYCDOjI&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video" frameborder="0" data-lity allowfullscreen>
                        <img src="/images/home/amanda.jpg" alt="">
                    </a>
                    <p class="title is-5 is-marginless-bottom"><strong>Dr. Amanda Frick, N.D., LAc</strong></p>
                    <em>Lead Naturopathic Doctor</em>
                    <p>Amanda has a Naturopathic Family Practice in Los Angeles. She specializes in nutrition, hormone balancing and digestive disorders. She is passionate about bringing the best aspects of modern and traditional medicine together to benefit her patients.</p>
                </div>
                <div class="column is-3-desktop is-half-tablet is-auto-desktop">
                    <a href="//www.youtube.com/watch?v=ch1GOHj5VOQ&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video" frameborder="0" data-lity allowfullscreen>
                        <img src="/images/home/rachel.jpg" alt="">
                    </a>
                    <p class="title is-5 is-marginless-bottom"><strong>Dr. Rachel West, D.O.</strong></p>
                    <em>Chief Medical Officer</em>
                    <p>Rachel is a board certified Integrative Family Medicine Physician and Osteopath in Los Angeles. Her work and professional experience has put her at the forefront of integrative and functional medicine.</p>
                </div>
                <div class="column is-3-desktop is-half-tablet is-auto-desktop is-hidden-mobile">
                    <img src="/images/home/empty.png" class="is-opaque-75" alt="">
                    <p class="title is-5 is-marginless-bottom"><strong>Are you an integrative doctor?</strong></p>
                    <em>Join the Harvey team</em>
                    <p class="is-6">Are you an integrative or naturopathic doctor with 5+ years of experience and a passion for holistic health and wellness? We are growing our clinical team at Harvey and would love to meet you. Contact us!</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="integrative-medicine">
        <div class="container">
            <h2 class="title is-4 section-header"><span>Integrative Medicine</span></h2>
            <div class="introduction columns is-narrow is-marginless-top">
                <div class="column is-paddingless">
                    <figure class="image is-margin is-padding">
                        <img src="/images/home/venn-diagram.png" alt="">
                    </figure>
                </div>
                <div class="column has-content-vertical-aligned">
                    <div class="content">
                        <h2 class="title is-4">An evolution in medicine.</h2>
                        <p class="subtitle is-6">Integrative medicine combines traditional healing, prevention and natural self-healing philosophies of Eastern medicine with advanced evidence-based research of Western medicine to treat the whole person, not just a set of isolated symptoms.</p>
                    </div>
                </div>
            </div>
            <div class="columns is-multiline is-narrow">
                <div class="column is-half columns is-mobile">
                    <figure class="icon-wrapper icon-wrapper-has-background is-lime image">
                        <span class="icon icon_medicine_1"></span>
                    </figure>
                    <div class="column is-paddingless-top">
                        <p class="title is-5 is-marginless"><strong>Holistic approach</strong></p>
                        <p class="subtitle is-6">A methodology addressing the body as a whole rather a collection of parts.</p>
                    </div>
                </div>
                <div class="column is-half columns is-mobile">
                    <figure class="icon-wrapper icon-wrapper-has-background is-pink image">
                        <span class="icon icon_medicine_2"></span>
                    </figure>
                    <div class="column is-paddingless-top">
                        <p class="title is-5 is-marginless"><strong>Medical school</strong></p>
                        <p class="subtitle is-6">100% of our doctors graduated from an accredited four-year residential medical school.</p>
                    </div>
                </div>
                <div class="column is-half columns is-mobile">
                    <figure class="icon-wrapper icon-wrapper-has-background is-brown image">
                        <span class="icon icon_medicine_3"></span>
                    </figure>
                    <div class="column is-paddingless-top">
                        <p class="title is-5 is-marginless"><strong>State medical license</strong></p>
                        <p class="subtitle is-6">100% of our doctors passed a medical board exam and received a state medical license.</p>
                    </div>
                </div>
                <div class="column is-half columns is-mobile">
                    <figure class="icon-wrapper icon-wrapper-has-background is-purple image">
                        <span class="icon icon_medicine_4"></span>
                    </figure>
                    <div class="column is-paddingless-top">
                        <p class="title is-5 is-marginless"><strong>Backed by science</strong></p>
                        <p class="subtitle is-6">We rely on advanced clinical laboratory tests to validate any proposed treatment plans.</p>
                    </div>
                </div>
                <div class="column is-half columns is-mobile">
                    <figure class="icon-wrapper icon-wrapper-has-background is-slategrey image">
                        <span class="icon icon_medicine_6"></span>
                    </figure>
                    <div class="column is-paddingless-top">
                        <p class="title is-5 is-marginless"><strong>Team approach</strong></p>
                        <p class="subtitle is-6">A multi-discipline team of doctors to address and treat your specific needs.</p>
                    </div>
                </div>
                <div class="column is-half columns is-mobile">
                    <figure class="icon-wrapper icon-wrapper-has-background is-green image">
                        <span class="icon icon_medicine_5"></span>
                    </figure>
                    <div class="column is-paddingless-top">
                        <p class="title is-5 is-marginless"><strong>Natural remedies</strong></p>
                        <p class="subtitle is-6">Our unique treatment plans may include diet, nutrition, supplementation or lifestyle changes.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="western-medicine">
        <div class="container">
            <div class="columns is-narrow is-multiline">
                <figure class="image image-has-max-height">
                    <picture>
                        <img class="hero-thumbnail" src="/images/home/stomach_md.jpg">
                    </picture>
                </figure>
                <div class="column is-6-desktop is-offset-1-desktop has-content-vertical-aligned">
                    <div class="content">
                        <h2 class="title is-4 is-3-widescreen is-padding-top"><strong>"Why do I feel this way?"</strong></h2>
                        <p>If you're feeling crummy, you may be part of the <strong>48% of adults</strong> who are battling a chronic health condition.</p>
                        <p>Why are Americans so sick? One reason is doctors in traditional medical practices spend on average less than 13 minutes with their patients and prescribe drugs over 80% of the time.</p>
                        <p>These patients are often failed by doctors who lack the time, education or financial incentives to heal their patients naturally. Without preventative treatment, these health issues can lead to more serious and costly diseases.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="pricing">
        <div class="container">
            <h2 class="title is-4 section-header"><span>Simple Pricing</span></h2>
            <div class="columns is-narrow is-marginless-top">
                <div class="column is-7 has-content-vertical-aligned is-paddingless">
                    <div class="content">
                        <h2 class="title is-4 is-marginless-bottom"><strong>Do you take insurance?</strong></h2>
                        <p class="is-6 is-margin is-marginless-left">We are not contracted with any insurance providers at this time, meaning we work just for you. Preventative healthcare expenditures and specialty lab tests are not reimbursable under most health plans. However, you may be able to use your HSA/FSA account to pay for our services.</p>
                        <h2 class="title is-4 is-marginless-bottom"><strong>How much are lab tests?</strong></h2>
                        <p class="is-6 is-margin is-marginless-left">While most clinics mark up the prices of their lab tests, we sell them at close to wholesale cost. <a href="lab-tests" alt="Lab Tests">Lab tests</a> start at $99 and are comparable to the out-of-pocket co-pays and deductibles you would pay at conventional medical clinics.</p>
                    </div>
                </div>
                <div class="column is-6 is-auto-tablet">
                    <figure>
                        <img src="/images/home/pricing.png" alt="">
                    </figure>
                </div>
            </div>
            <div class="has-text-centered">
                <div class="button-wrapper">
                    <a href="/get-started" class="button is-primary is-medium has-arrow">Book Appointment</a>
                </div>
            </div>
        </div>
    </section>

   <div class="anchor" id="scroll-here"></div>

    <section class="section" id="stories">
        <div class="container">
            <h2 class="title is-4 section-header"><span>Patient Stories</span></h2>
            <div class="columns is-narrow">
                <div class="column is-3-desktop is-half-tablet is-auto-desktop">
                    <a href="//www.youtube.com/watch?v=bOofWokoX5g&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video" frameborder="0" data-lity allowfullscreen>
                        <img src="/images/home/jill.jpg" alt="">
                    </a>
                    <p class="video-title is-5 is-marginless-bottom"><strong class="video-title">Jill's Story</strong></p>
                    <em>Avoided unnecessary surgery.</em>
                </div>
                <div class="column is-3-desktop is-half-tablet is-auto-desktop">
                    <a href="//www.youtube.com/watch?v=ewrP5mzbspM&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video" frameborder="0" data-lity allowfullscreen>
                        <img src="/images/home/layne.jpg" alt="">
                    </a>
                    <p class="video-title is-5 is-marginless-bottom"><strong class="video-title">Layne's Story</strong></p>
                    <em>Discovered high levels of mercury.</em>
                </div>
                <div class="column is-3-desktop is-half-tablet is-auto-desktop">
                    <a href="//www.youtube.com/watch?v=hc4SfhKhwcw&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video" frameborder="0" data-lity allowfullscreen>
                        <img src="/images/home/lauren.jpg" alt="">
                    </a>
                    <p class="video-title is-5 is-marginless-bottom"><strong class="video-title">Lauren's Story</strong></p>
                    <em>Healed from a thyroid disorder.</em>
                </div>
            </div>
            <div class="columns is-narrow is-hidden-mobile">
                <div class="column is-3-desktop is-half-tablet is-auto-desktop">
                    <a href="//www.youtube.com/watch?v=P35czqune48&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video" frameborder="0" data-lity allowfullscreen>
                        <img src="/images/home/scott.jpg" alt="">
                    </a>
                    <p class="video-title is-5 is-marginless-bottom"><strong class="video-title">Scott's Story</strong></p>
                    <em>Optimized recovery from injury.</em>
                </div>
                <div class="column is-3-desktop is-half-tablet is-auto-desktop">
                    <a href="//www.youtube.com/watch?v=iaHuXlV7CtY&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video" frameborder="0" data-lity allowfullscreen>
                        <img src="/images/home/jamie.jpg" alt="">
                    </a>
                    <p class="video-title is-5 is-marginless-bottom"><strong class="video-title">Jamie's Story</strong></p>
                    <em>Healed from chronic fatigue syndrome and Lyme disease.</em>
                </div>
                <div class="column is-3-desktop is-half-tablet is-auto-desktop">
                    <a href="//www.youtube.com/watch?v=Ydk2bfHraEY&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video" frameborder="0" data-lity allowfullscreen>
                        <img src="/images/home/bruce.jpg" alt="">
                    </a>
                    <p class="video-title is-5 is-marginless-bottom"><strong class="video-title">Bruce's Story</strong></p>
                    <em>Cured himself of diabetes.</em>
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
                        <img src="/images/home/micronutrient.jpg" alt="">
                    </figure>
                </div>
            </div>
            <hr>
            <div class="columns is-narrow">
                <div class="column">
                    <figure>
                        <img src="/images/home/allergy.jpg" alt="">
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
                        <img src="/images/home/hormone.jpg" alt="">
                    </figure>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="labs">
        <div class="container">
            <div class="columns is-narrow is-multiline">
                <figure class="image">
                    <img src="/images/home/package-door.jpg" alt="">
                </figure>
                <div class="column is-5-desktop is-offset-1-desktop is-12-tablet has-content-vertical-aligned">
                    <div class="content">
                        <h2 class="title is-3 is-padding-top"><strong>Lab testing delivered right to your doorstep.</strong></h2>
                        <p class="is-6">All our lab tests can be taken in the comfort of your home. If recommended by your doctor, we will mail you a lab kit and (if necessary) schedule a mobile phlebotomist to perform a free in-home blood draw.</p>
                        <div class="button-wrapper">
                            <a href="/lab-tests" class="button is-secondary is-outlined is-medium has-arrow">
                                <img src="/images/home/vial.png"> Lab Tests
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section is-paddingless is-marginless" id="social-feed">
        <ul class="juicer-feed" data-feed-id="goharveyapp" data-per="15"></ul>
        <div class="button-wrapper has-text-centered social-feed">
            <a href="//www.instagram.com/goharveyapp" class="button is-secondary is-outlined is-medium has-arrow" target="_blank">
                <img src="/images/home/instagram.png"> Follow Instagram</a>
        </div>
    </section>

    <section class="section" id="symptoms">
        <div class="section-wrapper">
            <div class="container content">
                <h2 class="title is-4 section-header"><span>Where We Can Help</span></h2>
                <p class="copy-has-max-width subtitle is-4-desktop is-5-tablet is-5-mobile has-text-centered">Our physicians have experience treating patients suffering from a wide range of small and complex health issues.</p>
                <div class="columns is-multiline is-narrow">
                    <div class="column columns is-half-tablet is-auto-desktop is-marginless is-paddingless-bottom">
                        <figure class="has-text-centered has-margin circle">
                            <img src="/images/home/square-11.png" alt="">
                        </figure>
                        <div class="column is-paddingless">
                            <label class="label">Cardiovascular</label>
                            <label class="symptoms-selector-description">Chest pain, dizziness, fainting, fevers, irregular heartbeat, shortness of breath, leg swelling, etc.</label>
                        </div>
                    </div>
                    <div class="column columns is-half-tablet is-auto-desktop is-marginless is-paddingless-bottom">
                        <figure class="has-text-centered has-margin circle">
                            <img src="/images/home/square-48.png" alt="">
                        </figure>
                        <div class="column is-paddingless">
                            <label class="label">Digestive</label>
                            <label class="symptoms-selector-description">Acid reflux, constipation, gas, diarrhea, heartburn, indigestion, bloating, stomach pain, cramps, etc.</label>
                        </div>
                    </div>
                </div>
                <div class="columns is-multiline is-narrow">
                    <div class="column columns is-half-tablet is-auto-desktop is-marginless is-paddingless-bottom">
                        <figure class="has-text-centered has-margin circle">
                            <img src="/images/home/square-2.png" alt="">
                        </figure>
                        <div class="column is-paddingless">
                            <label class="label">Endocrine/Hormonal</label>
                            <label class="symptoms-selector-description">Depression, anxiety, fatigue, hot flashes, insomnia, mood swings, night sweats, weight gain/loss, etc.</label>
                        </div>
                    </div>
                    <div class="column columns is-half-tablet is-auto-desktop is-marginless is-paddingless-bottom">
                        <figure class="has-text-centered has-margin circle">
                            <img src="/images/home/square-28.png" alt="">
                        </figure>
                        <div class="column is-paddingless">
                            <label class="label">Dermatological</label>
                            <label class="symptoms-selector-description">Hair, skin and nails weakness, and other exocrine gland issues.</label>
                        </div>
                    </div>
                </div>
                <div class="columns is-multiline is-narrow">
                    <div class="column columns is-half-tablet is-auto-desktop is-marginless is-paddingless-bottom">
                        <figure class="has-text-centered has-margin circle">
                            <img src="/images/home/square-26.png" alt="">
                        </figure>
                        <div class="column is-paddingless">
                            <label class="label">Immune</label>
                            <label class="symptoms-selector-description">Frequent colds, flus, cold sores, swollen lymph glands, or fighting an autoimmune diseases.</label>
                        </div>
                    </div>
                    <div class="column columns is-half-tablet is-auto-desktop is-marginless is-paddingless-bottom">
                        <figure class="has-text-centered has-margin circle">
                            <img src="/images/home/square-20.png" alt="">
                        </figure>
                        <div class="column is-paddingless">
                            <label class="label">Musculoskeletal</label>
                            <label class="symptoms-selector-description">Aches, muscle pain, body fatigue, loss of muscle control, etc.</label>
                        </div>
                    </div>
                </div>
                <div class="columns is-multiline is-narrow">
                    <div class="column columns is-half-tablet is-auto-desktop is-marginless is-paddingless-bottom">
                        <figure class="has-text-centered has-margin circle">
                            <img src="/images/home/square-14.png" alt="">
                        </figure>
                        <div class="column is-paddingless">
                            <label class="label">Nervous</label>
                            <label class="symptoms-selector-description">Headaches, migraines, numbness, tingling, tremors, etc.</label>
                        </div>
                    </div>
                    <div class="column columns is-half-tablet is-auto-desktop is-marginless is-paddingless-bottom">
                        <figure class="has-text-centered has-margin circle">
                            <img src="/images/home/square-51.png" alt="">
                        </figure>
                        <div class="column is-paddingless">
                            <label class="label">Renal/Urinary</label>
                            <label class="symptoms-selector-description">Loss of bladder control, urinary tract infection, liver/kidney issues, etc.</label>
                        </div>
                    </div>
                </div>
                <div class="columns is-multiline is-narrow">
                    <div class="column columns is-half-tablet is-auto-desktop is-marginless is-paddingless-bottom">
                        <figure class="has-text-centered has-margin circle">
                            <img src="/images/home/square-4.png" alt="">
                        </figure>
                        <div class="column is-paddingless">
                            <label class="label">Reproductive</label>
                            <label class="symptoms-selector-description">Impotence, loss of libido, pre/post-menopause, yeast infections, and other reproductive issues.</label>
                        </div>
                    </div>
                    <div class="column columns is-half-tablet is-auto-desktop is-marginless is-paddingless-bottom">
                        <figure class="has-text-centered has-margin circle">
                            <img src="/images/home/square-52.png" alt="">
                        </figure>
                        <div class="column is-paddingless">
                            <label class="label">Respiratory</label>
                            <label class="symptoms-selector-description">Allergies, breathing problems, chronic cough/cold issues, bronchial inflammation, etc.</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section" id="get-started">
        <div class="container">
            <div class="has-text-centered">
                <h2 class="title is-3 is-padding-bottom">Start your journey to better health.</h2>
                <div class="button-wrapper">
                    <a href="/get-started" class="button is-primary is-medium has-arrow">Book Appointment</a>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
