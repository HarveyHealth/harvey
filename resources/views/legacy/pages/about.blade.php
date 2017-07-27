@extends('legacy._layouts.public')
@section('page_title','About')
@section('main_content')

    <section class="hero">
        <div class="hero-background"></div>
        <div class="hero-body container">
            <header class="content has-text-centered">
                <h1 class="title is-3 page-title">About Us</h1>
                <p class="copy-has-max-width subtitle is-5 ">Harvey combines conventional Western therapies with scientifically-validated philosophies of Eastern medicine, such as prevention and natural self-healing, to treat the whole person, not just a set of isolated symptoms.</p>
            </header>
        </div>
    </section>

    <section class="section" id="integrative-medicine">
        <div class="container">
            <h2 class="title is-4 section-header"><span>Integrative Approach</span></h2>
            <div class="columns is-multiline is-narrow">
                <div class="column is-half columns is-mobile">
                    <figure class="icon-wrapper icon-wrapper-has-background is-lime">
                        <span class="icon icon_medicine_1"></span>
                    </figure>
                    <div class="column is-paddingless-top">
                        <p class="title is-5 is-marginless"><strong>Holistic approach</strong></p>
                        <p class="subtitle is-6">A methodology addressing the body as a whole rather a collection of parts.</p>
                    </div>
                </div>
                <div class="column is-half columns is-mobile">
                    <figure class="icon-wrapper icon-wrapper-has-background is-pink">
                        <span class="icon icon_medicine_2"></span>
                    </figure>
                    <div class="column is-paddingless-top">
                        <p class="title is-5 is-marginless"><strong>Medical school</strong></p>
                        <p class="subtitle is-6">100% of our doctors graduated from an accredited four-year residential medical school.</p>
                    </div>
                </div>
                <div class="column is-half columns is-mobile">
                    <figure class="icon-wrapper icon-wrapper-has-background is-brown">
                        <span class="icon icon_medicine_3"></span>
                    </figure>
                    <div class="column is-paddingless-top">
                        <p class="title is-5 is-marginless"><strong>State medical license</strong></p>
                        <p class="subtitle is-6">100% of our doctors passed a medical board exam and received a state medical license.</p>
                    </div>
                </div>
                <div class="column is-half columns is-mobile">
                    <figure class="icon-wrapper icon-wrapper-has-background is-purple">
                        <span class="icon icon_medicine_4"></span>
                    </figure>
                    <div class="column is-paddingless-top">
                        <p class="title is-5 is-marginless"><strong>Backed by science</strong></p>
                        <p class="subtitle is-6">We rely on advanced clinical laboratory tests to validate any proposed treatment plans.</p>
                    </div>
                </div>
                <div class="column is-half columns is-mobile">
                    <figure class="icon-wrapper icon-wrapper-has-background is-slategrey">
                        <span class="icon icon_medicine_6"></span>
                    </figure>
                    <div class="column is-paddingless-top">
                        <p class="title is-5 is-marginless"><strong>Team approach</strong></p>
                        <p class="subtitle is-6">A multi-discipline team of doctors to address and treat your specific needs.</p>
                    </div>
                </div>
                <div class="column is-half columns is-mobile">
                    <figure class="icon-wrapper icon-wrapper-has-background is-green">
                        <span class="icon icon_medicine_5"></span>
                    </figure>
                    <div class="column">
                        <p class="title is-5 is-marginless"><strong>Natural remedies</strong></p>
                        <p class="subtitle is-6">Our unique treatment plans may include diet, nutrition, supplementation or lifestyle changes.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section western is-paddingless-top" id="feature">
        <div class="container">
            <div class="columns is-narrow is-multiline">
                <figure class="image">
                    <img src="/images/about/diabetes.gif" alt="">
                </figure>
                <div class="column is-5-desktop is-12-tablet has-content-vertical-aligned">
                    <div class="content">
                        <h2 class="title is-3"><strong>Our healthcare system is not meant to keep you healthy.</strong></h2>
                        <p>According to the CDC, <strong>48% of adults</strong> are battling one or more chronic health conditions. One reason is medical doctors spend on average less than 13 minutes with their patients and prescribe drugs over 80% of the time.</p>
                        <p>Fortunately, Harvey doctors have the time, education and financial incentives to provide you with proper preventative treatments and help you heal naturally.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="stories">
        <div class="container">
            <h2 class="title is-4 section-header"><span>Leadership</span></h2>
            <div class="columns is-narrow">
                <div class="column is-3-desktop is-half-tablet is-auto-desktop">
                    <a href="//www.youtube.com/watch?v=ZyTK8qn_GAI&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video" frameborder="0" data-lity allowfullscreen>
                        <img src="/images/about/kyle.jpg" alt="">
                    </a>
                    <p class="video-title is-5 is-marginless-bottom">Kyle Hill</p>
                    <em>Founder & CEO</em>
                    <p>Kyle has a finance and design background with 10+ years of experience building disruptive tech companies, including CEO at HomeHero, a home care marketplace. Kyle is a TEDx speaker and made Forbes “30 Under 30” list in healthcare in 2016.</p>
                </div>
                <div class="column is-3-desktop is-half-tablet is-auto-desktop">
                    <a href="//www.youtube.com/watch?v=DBjYDaecNgM&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video" frameborder="0" data-lity allowfullscreen>
                        <img src="/images/about/mike.jpg" alt="">
                    </a>
                    <p class="video-title is-5 is-marginless-bottom">Mike Townsend</p>
                    <em>Founder & COO</em>
                    <p>Mike is a serial entrepreneur with an extensive background in healthcare and engineering. Mike was also a founder at HomeHero and named to Forbes 30/30 in healthcare. He has a passion for science and evidence-based medicine.</p>
                </div>
                <div class="column is-3-desktop is-half-tablet is-auto-desktop">
                    <a href="//www.youtube.com/watch?v=ch1GOHj5VOQ&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video" frameborder="0" data-lity allowfullscreen>
                        <img src="/images/about/rachel.jpg" alt="">
                    </a>
                    <p class="video-title is-5 is-marginless-bottom">Dr. Rachel West, D.O.</p>
                    <em>Chief Medical Officer</em>
                    <p>Dr. West is a board-certified integrative family medicine physician and osteopath in Los Angeles. Her work and professional experience have put her at the forefront of integrative, functional and preventative medicine.</p>
                </div>
            </div>
            <div class="columns is-narrow">
                <div class="column is-3-desktop is-half-tablet is-auto-desktop">
                    <a href="//www.youtube.com/watch?v=2bjmlYCDOjI&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video" frameborder="0" data-lity allowfullscreen>
                        <img src="/images/about/amanda.jpg" alt="">
                    </a>
                    <p class="video-title is-5 is-marginless-bottom">Dr. Amanda Frick, N.D., LAc</p>
                    <em>Lead Naturopathic Doctor</em>
                    <p>Dr. Frick is a licensed Naturopathic Doctor from San Diego. She specializes in nutrition, hormone balancing and digestive disorders. She is passionate about bringing the best aspects of modern and traditional medicine together to benefit her patients.</p>
                </div>
                <div class="column is-3-desktop is-half-tablet is-auto-desktop">
                    <a href="//www.youtube.com/watch?v=ewrP5mzbspM&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video" frameborder="0" data-lity allowfullscreen>
                        <img src="/images/about/layne.jpg" alt="">
                    </a>
                    <p class="video-title is-5 is-marginless-bottom"><strong class="video-title">Layne Thrasher</strong></p>
                    <em>VP of Finance/Operations</em>
                    <p>Layne has a diverse background that includes finance, accounting, operations and marketing. He has trusted integrative physicians to keep him healthy for many years. He believes nutrition, active lifestyle and advanced lab testing are critical to maximizing your healthspan.</p>
                </div>
                <div class="column is-3-desktop is-half-tablet is-auto-desktop">
                    <a href="//www.youtube.com/watch?v=wAUQgwbUUYA&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video" frameborder="0" data-lity allowfullscreen>
                        <img src="/images/about/sandra.jpg" alt="">
                    </a>
                    <p class="video-title is-5 is-marginless-bottom">Sandra Walker</p>
                    <em>Senior Operations Manager</em>
                    <p>Sandra is the soul of our Operations team at Harvey, making sure every client has an amazing experience. She has a passion for nutrition, holistic medicine, plant-based diets and helping patients on their journey to better health.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="symptoms">
        <div class="section-wrapper">
            <div class="container content">
                <h2 class="title is-4 section-header"><span>Specialities</span></h2>
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
                            <label class="label">Fertility/Reproductive</label>
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


@endsection
