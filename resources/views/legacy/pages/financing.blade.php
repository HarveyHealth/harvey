@extends('legacy._layouts.public')
@section('page_title','Financing')
@section('main_content')

    <section class="hero hero-background">
        <div id="hero-video-container">
            <video id="hero-video" autoplay loop muted></video>
            <div id="video-cover"></div>
            <div id="overlay"></div>
        </div>
        <div class="hero-body">
            <div class="container">
                <div class="columns">
                    <div class="column is-7 is-6-desktop is-offset-5">
                        <h1 class="title is-1">Financing</h1>
                        <p class="subtitle is-5">Harvey empowers people to find natural and holistic remedies to chronic health conditions. We believe in science, and we believe in treating your whole body, not just your symptoms.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container has-text-centered">
            <h2 class="title is-3">Your health is our <strong>#1 priority</strong>.</h2>
            <p class="copy-has-max-width subtitle is-4-desktop is-5-mobile">Harvey's doctors take a preventative and relationship-driven approach to medicine, with an emphasis on nutrition, lifestyle and environment factors, to help you find the root cause of chronic health conditions (both big and small) and reduce risk of serious disease.</p>
            <div class="button-wrapper">
                <a href="/about" class="button is-secondary is-outlined is-medium has-arrow">Learn More</a>
            </div>
        </div>
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

    <section class="section" id="get-started">
        <div class="container">
            <div class="has-text-centered">
                <h2 class="title is-3 is-padding-bottom">Start your journey to better health.</h2>
                <div class="button-wrapper">
                    <a href="/conditions" class="button is-primary is-medium has-arrow">Get Started</a>
                </div>
            </div>
        </div>
    </section>

@endsection
