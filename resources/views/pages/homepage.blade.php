@extends('_layouts.public')
@section('page_title','Welcome to Harvey')
@section('body_class','home')

@section('content')
<div class="sections">
    <section class="hero is-fullheight is-primary">
        <div class="hero-background"></div>
        <div class="hero-body">
            <div class="container">
                <div class="columns">
                    <div class="column is-7-tablet is-6-desktop">
                        <h1 class="title is-1">It’s time to think differently about your medicine.</h1>
                        <p class="subtitle">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean condimentum velit sit amet lacinia ornare.</p>
                        <div class="button-wrapper">
                            <a href="/signup" class="button is-primary is-medium">Get Started</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container has-text-centered">
            <h2 class="title is-2">Some really big headline.</h2>
            <p class="copy-has-max-width subtitle is-5">Harvey is an online telehealth provider of integrative medicine. Consult face-to-face with state-licensed naturopathic doctors and conduct lab tests right in the comfort of your home.</p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <h2 class="title is-3 section-header"><span>Naturopathic Medicine</span></h2>
            <div class="columns is-multiline">
                <div class="column is-half columns is-mobile">
                    <figure class="icon-wrapper icon-wrapper-has-background is-lime image">
                        <span class="icon icon_comparison_1"></span>
                    </figure>
                    <div class="column">
                        <p class="title is-5"><strong>This is a small header</strong></p>
                        <p class="subtitle is-6">An emphasis on disease prevention</p>
                    </div>
                </div>
                <div class="column is-half columns is-mobile">
                    <figure class="icon-wrapper icon-wrapper-has-background is-pink image">
                        <span class="icon icon_comparison_2"></span>
                    </figure>
                    <div class="column">
                        <p class="title is-5"><strong>This is a small header</strong></p>
                        <p class="subtitle is-6">An individual approach to medicine</p>
                    </div>
                </div>
                <div class="column is-half columns is-mobile">
                    <figure class="icon-wrapper icon-wrapper-has-background is-brown image">
                        <span class="icon icon_comparison_3"></span>
                    </figure>
                    <div class="column">
                        <p class="title is-5"><strong>This is a small header</strong></p>
                        <p class="subtitle is-6">Nutritional and holistic treatments</p>
                    </div>
                </div>
                <div class="column is-half columns is-mobile">
                    <figure class="icon-wrapper icon-wrapper-has-background is-purple image">
                        <span class="icon icon_comparison_4"></span>
                    </figure>
                    <div class="column">
                        <p class="title is-5"><strong>This is a small header</strong></p>
                        <p class="subtitle is-6">Identifying the root cause of medical conditions</p>
                    </div>
                </div>
                <div class="column is-half columns is-mobile">
                    <figure class="icon-wrapper icon-wrapper-has-background is-purple image">
                        <span class="icon icon_comparison_4"></span>
                    </figure>
                    <div class="column">
                        <p class="title is-5"><strong>This is a small header</strong></p>
                        <p class="subtitle is-6">Identifying the root cause of medical conditions</p>
                    </div>
                </div>
                <div class="column is-half columns is-mobile">
                    <figure class="icon-wrapper icon-wrapper-has-background is-purple image">
                        <span class="icon icon_comparison_4"></span>
                    </figure>
                    <div class="column">
                        <p class="title is-5"><strong>This is a small header</strong></p>
                        <p class="subtitle is-6">Identifying the root cause of medical conditions</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section is-paddingless-mobile">
        <div class="container">
            <div class="columns">
                <div class="column">
                    <figure>
                        <picture>
                            <source media="(max-width: 767px)" srcset="/images/home/background_2_sm.jpg">
                            <source media="(min-width: 768px)" srcset="/images/home/background_2_md.jpg">
                            <img class="hero-thumbnail" src="/images/home/background_2_md.jpg" alt="Harvey">
                        </picture>
                    </figure>
                </div>
                <div class="column has-content-vertical-aligned">
                    <div class="content has-padding-left has-padding-right">
                        <h2 class="title is-3"><strong>This is a medium header</strong></h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sagittis est sit amet lorem pulvinar tincidunt. Nullam imperdiet felis ac velit venenatis aliquet. In ut justo eros. Ut venenatis nisl at tincidunt maximus. Nulla nisi nibh, condimentum quis sodales sed.</p>
                        <p>Phasellus porttitor venenatis urna, vel volutpat risus volutpat at. Duis ullamcorper nibh sit amet mauris sagittis, in venenatis urna lacinia. Suspendisse elit mi, tempor vel tellus et, convallis porttitor magna. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia imsum.</p>
                        <div class="button-wrapper">
                            <a href="/signup" class="button is-primary is-medium">Get Started</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="tests">
        <div class="container">
            <h2 class="title is-3 section-header"><span>Type of Tests</span></h2>
            <div class="columns has-border-bottom">
                <div class="column has-content-vertical-aligned">
                    <div class="content">
                        <h2 class="title is-4"><strong>This is a medium header</strong></h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sagittis est sit amet lorem pulvinar tincidunt. Nullam imperdiet felis ac velit venenatis aliquet. In ut justo eros. Ut venenatis nisl at tincidunt maximus. Nulla nisi nibh, condimentum quis sodales sed.</p>
                    </div>
                </div>
                <div class="column has-text-right">
                    <figure>
                        <img src="/images/home/test_1.png" alt="">
                    </figure>
                </div>
            </div>
            <div class="columns has-border-bottom">
                <div class="column">
                    <figure>
                        <img src="/images/home/test_2.png" alt="">
                    </figure>
                </div>
                <div class="column has-content-vertical-aligned">
                    <div class="content">
                        <h2 class="title is-4"><strong>This is a medium header</strong></h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sagittis est sit amet lorem pulvinar tincidunt. Nullam imperdiet felis ac velit venenatis aliquet. In ut justo eros. Ut venenatis nisl at tincidunt maximus. Nulla nisi nibh, condimentum quis sodales sed.</p>
                    </div>
                </div>
            </div>
            <div class="columns">
                <div class="column has-content-vertical-aligned">
                    <div class="content">
                        <h2 class="title is-4"><strong>This is a medium header</strong></h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sagittis est sit amet lorem pulvinar tincidunt. Nullam imperdiet felis ac velit venenatis aliquet. In ut justo eros. Ut venenatis nisl at tincidunt maximus. Nulla nisi nibh, condimentum quis sodales sed.</p>
                    </div>
                </div>
                <div class="column has-text-right">
                    <figure>
                        <img src="/images/home/test_3.jpg" alt="">
                    </figure>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="pricing">
        <div class="container">
            <div class="columns">
                <figure>
                    <img src="/images/home/pricing.jpg" alt="">
                </figure>
                <div class="column is-6-tablet is-5 is-offset-1-desktop has-content-vertical-aligned">
                    <div class="content">
                        <h2 class="title is-3"><strong>Pricing that makes sense</strong></h2>
                        <p class="subtitle is-5">We charge <strong>$149</strong> for each 1-hour consultation with our doctors. Our speciality lab tests range in price from $29 to $299 depending on complexity.</p>
                        <div class="button-wrapper">
                            <a href="" class="button is-primary is-medium">Review Lab Tests</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="columns">
                <div class="column is-8-desktop is-offset-2-desktop columns">
                    <blockquote class="column">"If we do not improve at least one of your top three medical symptoms or conditions, we will offer a 100% refund."</blockquote>
                    <figure class="is-hidden-mobile">
                        <img src="/images/home/blockquote.jpg" alt="">
                    </figure>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="how-it-works">
        <div class="container">
            <h2 class="title is-3 section-header"><span>How it Works</span></h2>
            <div class="columns is-multiline">
                <div class="column columns is-mobile is-half-tablet is-auto-desktop">
                    <figure class="icon-wrapper icon-wrapper-has-background is-pink image">
                        <span class="icon icon_steps_1"></span>
                    </figure>
                    <div class="column">
                        <p class="title is-5"><strong>1. Consultation</strong></p>
                        <p>Consult over the phone with a state-licensed naturopathic doctor.</p>
                    </div>
                </div>
                <div class="column columns is-mobile is-half-tablet is-auto-desktop">
                    <figure class="icon-wrapper icon-wrapper-has-background is-turquoise image">
                        <span class="icon icon_steps_2"></span>
                    </figure>
                    <div class="column">
                        <p class="title is-5"><strong>2. Blood test</strong></p>
                        <p>Perform one or more of our highly specialized lab tests in the comfort of your home.</p>
                    </div>
                </div>
                <div class="column columns is-mobile is-half-tablet is-auto-desktop">
                    <figure class="icon-wrapper icon-wrapper-has-background is-slategrey image">
                        <span class="icon icon_steps_3"></span>
                    </figure>
                    <div class="column">
                        <p class="title is-5"><strong>3. Analysis</strong></p>
                        <p>Identify the root causes of your symptoms with your doctor by reviewing each biomarker.</p>
                    </div>
                </div>
                <div class="column columns is-mobile is-half-tablet is-auto-desktop">
                    <figure class="icon-wrapper icon-wrapper-has-background is-green image">
                        <span class="icon icon_steps_4"></span>
                    </figure>
                    <div class="column">
                        <p class="title is-5"><strong>4. Prescriptions</strong></p>
                        <p>Receive prescriptions for all-natural vitamins, supplements, and diet and lifestyle changes.</p>
                    </div>
                </div>
            </div>
            <div class="button-wrapper has-text-centered">
                <a href="/signup" class="button is-primary is-medium">Get Started</a>
            </div>
        </div>
    </section>
    
    <section class="section">
        <div class="container">
            <h2 class="title is-3 section-header"><span>Learn the Facts</span></h2>
            <vertical-tabs>
                <vertical-tab label="Doctors & nutrition">
                    <h3 class="title is-4"><strong>Doctors &amp; nutrition</strong></h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deserunt dolorum quas amet cum vitae, omnis! Illum quas voluptatem, expedita iste, dicta ipsum ea veniam dolore in, quod saepe reiciendis nihil.</p>
                </vertical-tab>
                <vertical-tab label="Leading causes of death">
                    <h3 class="title is-4"><strong>Leading causes of death</strong></h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deserunt dolorum quas amet cum vitae, omnis! Illum quas voluptatem, expedita iste, dicta ipsum ea veniam dolore in, quod saepe reiciendis nihil.</p>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deserunt dolorum quas amet cum vitae, omnis! Illum quas voluptatem, expedita iste, dicta ipsum ea veniam dolore in, quod saepe reiciendis nihil.</p>
                </vertical-tab>
                <vertical-tab label="Specialized lab testing">
                    <h3 class="title is-4"><strong>Specialized lab testing</strong></h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deserunt dolorum quas.</p>
                </vertical-tab>
                <vertical-tab label="Clinical evidence">
                    <h3 class="title is-4"><strong>Clinical evidence</strong></h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deserunt dolorum quas.</p>
                </vertical-tab>
                <vertical-tab label="Naturopathic doctors">
                    <h3 class="title is-4"><strong>Naturopathic doctors</strong></h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deserunt dolorum quas.</p>
                </vertical-tab>
                <vertical-tab label="Naturopathic treatments">
                    <h3 class="title is-4"><strong>Naturopathic treatments</strong></h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deserunt dolorum quas.</p>
                </vertical-tab>
                <vertical-tab label="Identifying quackery">
                    <h3 class="title is-4"><strong>Identifying quackery</strong></h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deserunt dolorum quas.</p>
                </vertical-tab>
            </vertical-tabs>
        </div>
    </section>

    <section class="section" id="symptoms">
        <div class="section-wrapper">
            <div class="container content">
                <h2 class="title is-3 section-header"><span>Your symptoms</span></h2>
                <symptoms :stats="symptomsStats" @changed="onChanged"></symptoms>
                <div class="has-text-centered">
                    <p class="disclaimer">Your selections above will be saved and shared with your doctor before your first consultation.</p>
                    <button class="button is-primary is-medium" @click="getStarted" :disabled="symptomsSaving">
                        <span class="icon"
                            v-if="symptomsSaving"
                        >
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
