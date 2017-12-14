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
                        <p class="subtitle is-5"> Nullam quam odio, ultrices sit amet accumsan vitae, dapibus eget metus. Duis quis dolor id mi placerat bibendum. Nulla laoreet dignissim orci vel interdum. Aenean condimentum, magna non elementum vulputate.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section how-it-works">
        <div class="container tc">
            <h2 class="title font-lg section-header"><span>Our Services</span></h2>
            <div class="columns">
                <div class="column">
                    <figure class="icon-wrapper icon-wrapper-has-background is-gray">
                        <span class="icon icon_steps_1"></span>
                    </figure>
                    <div class="pt3">
                        <p class="font-xxl"><strong>Consultations</strong></p>
                        <p class="is-3">Meet virtually with a Naturopathic Doctor about your health goals and receive a natural treatment plan and a supplement recommendations.</p>
                        <div class="pt3">
                            <a href="/#conditions" class="button is-primary is-medium has-arrow">
                                Book Consult
                            </a>
                        </div>
                    </div>
                </div>
                <div class="column">
                    <figure class="icon-wrapper icon-wrapper-has-background is-gray">
                        <span class="icon icon_steps_2"></span>
                    </figure>
                    <div class="pt3">
                        <p class="font-xxl"><strong>Lab Testing</strong></p>
                        <p class="is-3">Order one of our convenient in-home lab tests and review your results with a doctor who has a deep understanding of the test.</p>
                        <div class="pt3">
                            <a href="/lab-tests" class="button is-primary is-medium has-arrow">
                                Shop Lab Tests
                            </a>
                        </div>
                    </div>
                </div>
                <div class="column">
                    <figure class="icon-wrapper icon-wrapper-has-background is-gray">
                        <span class="icon icon_steps_4"></span>
                    </figure>
                    <div class="pt3">
                        <p class="font-xxl"><strong>Pharmacy</strong></p>
                        <p>Shop the industry's largest online dispensory of clinical-grade vitamins and supplements. Harvey Health members save 30% off every order.</p>
                        <div class="pt3">
                            <a href="/supplements" class="button is-primary is-medium has-arrow">
                                Enter Pharmacy
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="anchor" id="conditions"></div>

    <section class="section how-it-works">
        <div class="container">
            <h2 class="title font-lg section-header"><span>Our Approach</span></h2>
            <div class="tc has-max-width-xl">
                <p class="copy-has-max-width subtitle is-4-desktop is-5-mobile">Choose a category below to watch patient stories and learn how our integrative doctors are approaching various health conditions.</p>
                <div class="columns is-margin-top">
                    <div class="column" v-for="(condition, index) in conditions" v-if="index < 4">
                        <a :href="'/conditions/' + condition.slug">
                            <figure :class="'icon-wrapper icon-wrapper-has-background expand ' + State.conditionIconColors[index]">
                                <img class="icon full" :src="condition.image_url">
                            </figure>
                        </a>
                        <div class="pt2">
                            <p class="font-lg"><strong v-text="condition.name"></strong></p>
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
                        <div class="pt2">
                            <p class="font-lg"><strong v-text="condition.name"></strong></p>
                            <!-- <p v-if="State.conditionSubText[index]" v-text="State.conditionSubText[index]"></p> -->
                            <a :href="'/conditions/' + condition.slug">Learn More</a>
                        </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section mt2">
        <div class="container">
            <h2 class="title font-lg section-header"><span>Our Brands</span></h2>
        </div>
        <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/home/brands.png" class="mw100">
    </section>

    <section class="section mt0 pt3" id="social-feed">
        <div class="container">
            <h2 class="title font-lg section-header pt0 pb5"><span>Our Community</span></h2>
        </div>
        <ul class="juicer-feed" data-feed-id="goharveyapp" data-per="15"></ul>
    </section>

    <div class="anchor" id="prices"></div>

    <section class="section" id="pricing">
        <div class="container">
            <h2 class="title font-lg section-header"><span>Our Prices</span></h2>
            <div class="columns is-narrow is-marginless">
                <div class="column is-6 is-offset-1-desktop has-content-vertical-aligned is-paddingless">
                    <div class="content">

                        <p class="font-xl"><strong>Video Consultations</strong></p>
                        <p class="font-lg">Consultations with our board-certified Naturopathic Doctors are $75 for 30 minutes or $150 for 1 hour. <span class="green font-sm"><i class="fa fa-check-circle-o font-sm pt2" aria-hidden="true"></i> HSA/FSA</span></p>

                        <div class="mt2">
                            <a href="/#conditions" class="button is-primary font-md has-arrow">Book Consult</a>
                        </div>

                        <p class="font-xl mt4"><strong>Home Lab Tests</strong></p>
                        <p class="font-lg">Our in-home lab tests range from $199 to $399, depending on if a blood draw is required. <span class="green font-sm"><i class="fa fa-check-circle-o font-sm pt2" aria-hidden="true"></i> HSA/FSA</span></p>

                        <div class="mt2">
                            <a href="/lab-tests" class="button is-primary font-md has-arrow">Shop Lab Tests</a>
                        </div>

                        <p class="font-xl mt4"><strong>Quality Supplements</strong></p>
                        <p class="font-lg">Search professional-grade nutraceuticals across 300 top brands and save 20-30% off MSRP on every order. <span class="green font-sm"><i class="fa fa-check-circle-o font-sm pt2" aria-hidden="true"></i> HSA/FSA</span></p>

                        <div class="mt2">
                            <a href="/#conditions" class="button is-primary font-md has-arrow">Enter Pharmacy</a>
                        </div>

                    </div>
                </div>
                <div class="column is-6 is-auto-tablet tc">
                    <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/home/medicine-ball.png" class="w-100">
                </div>
            </div>
        </div>
    </section>

    <section class="section get-started mt4 pv3">
        <div class="container">
            <div class="tc has-max-width-lg pv3">
                <h2 class="font-xxl">Start your health journey.</h2>
                <p class="font-xl pa3">Lorem ipsum dolor sit amet, consectetur adipiscing elit. In sagittis massa et urna convallis tristique. Aenean varius, felis et auctor auctor, turpis nulla malesuada nurpis.</p>
            </div>
        </div>
    </section>
</div>

@endsection
