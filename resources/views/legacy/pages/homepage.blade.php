@extends('legacy._layouts.public')
@section('page_title','Holistic and Integrative Medicine')
@section('body_class','home')
@section('main_content')

<div class="sections check-load" :class="{'is-loaded': appLoaded}">

    <section id="hero-background">
        <div id="hero-video-container" class="o-80">
            <video id="hero-video" autoplay loop muted></video>
            <div id="video-cover"></div>
            <div class="overlay"></div>
        </div>
        <div class="container">
            <div class="tc pt6-ns pb4-ns ph7-ns">
                <h1 class="f1 lh-title mt0 mb3 white">Learn what your body really needs to feel its best.</h1>
                <p class="f4 fw5 mb4 white">Harvey offers preventative lab tests and clinical-grade vitamins and supplements—under the guidance of integrative doctors.</p>
                <div class="tc">
                    <a href="https://store.goharvey.com" class="button f3 ph4 is-primary has-arrow">Start Shopping</a>
                    <p class="f5 fw5 db cf ma3 white">Questions? <a href="/consultations" class="underline white dim">Chat with a doctor</a></p>
                </div>
            </div>
        </div>
    </section>

    <section id="products">
        <div class="container">
            <div class="flex tc pv5">
                <div class="w-third">
                    <div class="pa4 br-100 dib o-80 brown-bg">
                        <span class="icon icon_steps_2 h60 w60 dib"></span>
                    </div>
                    <div class="pa2">
                        <p class="f3 fw5 pt2">Home Lab Tests</p>
                        <p class="f5 pa3">Order one of our specialized home lab tests, such as <a href="https://store.goharvey.com/products/food-allergy-lab-test">Food Allergies</a>, <a href="https://store.goharvey.com/products/micronutrient-lab-test">Micronutrient</a>, <a href="https://store.goharvey.com/products/microbiome-lab-test">Microbiome</a>, and many more.</p>
                        <a href="https://store.goharvey.com/collections/lab-tests" class="f5 ba ph3 pv2 mb3 dib br2 mt3 dim"><i class="fa fa-shopping-cart pr1" aria-hidden="true"></i> Shop Now</a>
                    </div>
                </div>
                <div class="w-third">
                    <div class="pa4 br-100 dib o-80 brown-bg">
                      <span class="icon icon_steps_4 h60 w60 dib"></span>
                    </div>
                    <div class="pa2">
                        <p class="f3 fw5 pt2">Vitamins & Supplements</p>
                        <p class="f5 pa3">Shop the largest online dispensory of professional-grade vitamins and supplements at guaranteed wholesale prices.</p>
                        <a href="https://store.goharvey.com/collections/supplements" class="f5 ba ph3 pv2 mb3 dib br2 mt3 dim"><i class="fa fa-shopping-cart pr1" aria-hidden="true"></i> Shop Now</a>
                    </div>
                </div>
                <div class="w-third">
                    <div class="pa4 br-100 dib o-80 brown-bg">
                      <span class="icon icon_steps_1 h60 w60 dib"></span>
                    </div>
                    <div class="pa2">
                        <p class="f3 fw5 pt2">Functional Doctors</p>
                        <p class="f5 pa3">Chat for free with a licensed Naturopathic Doctor about your health goals, or schedule a full consultation to build a treatment plan.</p>
                        <a href="/consultations" class="f5 ba ph3 pv2 mb3 dib br2 mt3 dim"><i class="fa fa-stethoscope pr1" aria-hidden="true"></i> Meet Doctors</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="featured-video" class="o-70">
        <div class="container flex-l pv4">
            <div class="w-50-l pv4 tc">
                <a href="//www.youtube.com/watch?v=nfyk7irbYsw&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video dim" frameborder="0" data-lity allowfullscreen>
                    <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/home/nutrition.jpg" class="w-100 w-60-ns br2">
                </a>
            </div>
            <div class="w-50-l pt4">
                <div class="w-100 white center v-mid">
                    <p class="f2 pt3 white">#1 Integrative Medicine Platform</p>
                    <p class="f4 pt2 white">Harvey empowers people to find natural treatments to chronic health conditions. We believe in science, and we believe in treating the whole body, not just a set of symptoms.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="services" style="background-image: url(http://harvey-production.s3.amazonaws.com/assets/images/home/symbols.png)">
        <div class="container flex-l">
            <div class="tc pt5 ph6">
                <p class="f2">Private & Personalized Treatment</p>
                <p class="f4 pv3 ph4">Your health shouldn't be a guessing game on Google. Our doctors have successfully treated hundreds of patients fighting skin issues, food allergies, stress and anxiety, digestive issues, fatigue, weight gain/loss, hormonal changes, and many other health conditions.</p>
                <div class="db cf ma2 f4">
                    <a href="/consultations#stories"><i class="fa fa-play-circle pt1" aria-hidden="true"></i> Watch Patient Stories</a>
                </div>
            </div>
        </div>
        <div class="container flex-l">
            <div class="flex-ns flex-wrap-ns pt4 pb5">
                <div class="w-100 w-50-ns tc">
                    <div class="ph5 mb4">
                      <i class="fa fa-pagelines f2 ma1" aria-hidden="true"></i>
                      <p class="f3 fw3 mv2">Naturopathic Doctors</p>
                      <p class="f4">All of our doctors graduated from four-year residential medical school, passed a national board exam and have a state medical license.</p>
                    </div>
                </div>
                <div class="w-100 w-50-ns tc">
                    <div class="ph5 mb4">
                      <i class="fa fa-balance-scale f2 ma1" aria-hidden="true"></i>
                      <p class="f3 fw3 mv2">Alternative Approach</p>
                      <p class="f4">Harvey doctors take whole-body, root-cause approach to medicine. We view the body as one integrated system, not a collection of organs.</p>
                    </div>
                </div>
                <div class="w-100 w-50-ns tc">
                    <div class="ph5 mb4">
                      <i class="fa fa-flask f2 ma1" aria-hidden="true"></i>
                      <p class="f3 fw3 mv2">Advanced Lab Testing</p>
                      <p class="f4">We offer specialized home lab tests you can't get at your primary doctor. Most lab tests you can take from the comfort of your home.</p>
                    </div>
                </div>
                <div class="w-100 w-50-ns tc">
                    <div class="ph5 mb4">
                      <i class="fa fa-cutlery f2 ma1" aria-hidden="true"></i>
                      <p class="f3 fw3 mv2">Nutritional Guidance</p>
                      <p class="f4">Our natural treatment plans may include vitamins, supplements, or diet and lifestyle changes. We will never prescribe pharmaceuticals.</p>
                    </div>
                </div>
                <div class="w-100 w-50-ns tc">
                    <div class="ph5 mb4">
                      <i class="fa fa-desktop f2 ma1" aria-hidden="true"></i>
                      <p class="f3 fw3 mv2">Accessable Records</p>
                      <p class="f4">Manage all your appointments, lab results, supplement prescriptions and treatment plans from one central dashboard.</p>
                    </div>
                </div>
                <div class="w-100 w-50-ns tc">
                    <div class="ph5 mb4">
                      <i class="fa fa-comments-o f2 ma1" aria-hidden="true"></i>
                      <p class="f3 fw3 mv2">Free Messaging</p>
                      <p class="f4">We offer unlimited free chat with your doctor through a private Telegram channel. Full video consultations start at $75.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="press" class="bt b--near-white">
        <div class="container">
            <div class="tc pt4 ph7">
                <p class="f3 moon-gray">Don't just take our word for it.</p>
            </div>
            <div class="flex flex-wrap pa4">
                <div class="ph3 ph5-ns pv3 w-50 w-33-m w-25-ns tc">
                    <a href="https://www.youtube.com/watch?v=Q9usyL_Zs-A" class="dim">
                        <img src="http://harvey-production.s3.amazonaws.com/assets/images/home/press/quality-talks.png">
                    </a>
                </div>
                <div class="ph3 ph5-ns pv3 w-50 w-33-m w-25-ns tc">
                    <a href="http://google.com" class="dim">
                        <img src="http://harvey-production.s3.amazonaws.com/assets/images/home/press/cnbc.png">
                    </a>
                </div>
                <div class="ph3 ph5-ns pv3 w-50 w-33-m w-25-ns tc">
                    <a href="http://google.com" class="dim">
                        <img src="http://harvey-production.s3.amazonaws.com/assets/images/home/press/techcrunch.png">
                    </a>
                </div>
                <div class="ph3 ph5-ns pv3 w-50 w-33-m w-25-ns tc">
                    <a href="http://google.com" class="dim">
                        <img src="http://harvey-production.s3.amazonaws.com/assets/images/home/press/well-and-good.png">
                    </a>
                </div>
                <div class="ph3 ph5-ns pv3 w-50 w-33-m w-25-ns tc">
                    <a href="http://www.adweek.com/digital/5-workplace-tech-tools-every-office-should-use-in-2018/" class="dim">
                        <img src="http://harvey-production.s3.amazonaws.com/assets/images/home/press/adweek.png">
                    </a>
                </div>
                <div class="ph3 ph5-ns pv3 w-50 w-33-m w-25-ns tc">
                    <a href="http://google.com" class="dim">
                        <img src="http://harvey-production.s3.amazonaws.com/assets/images/home/press/ted.png">
                    </a>
                </div>
                <div class="ph3 ph5-ns pv3 w-50 w-33-m w-25-ns tc">
                    <a href="http://google.com" class="dim">
                        <img src="http://harvey-production.s3.amazonaws.com/assets/images/home/press/bustle.png">
                    </a>
                </div>
                <div class="ph3 ph5-ns pv3 w-50 w-33-m w-25-ns tc">
                    <a href="http://www.organicauthority.com/curious-about-acupuncture-but-scared-of-needles-check-out-these-new-techniques-that-celebs-love" class="dim">
                        <img src="http://harvey-production.s3.amazonaws.com/assets/images/home/press/organic-authority.png">
                    </a>
                </div>
            </div>
            <div class="container">
                <div class="bb b--near-white"></div>
            </div>
        </div>
    </section>

    <section id="get-started" class="bg-near-white">
        <div class="container">
            <div class="tc pv5 ph6-ns">
                <p class="f2">Start your health journey.</p>
                <p class="f4 pa3">If you're new to integrative medicine, we recommend chatting with one of our doctors before buying lab tests or supplements. If you know what you want, you can start shopping now.</p>
                <div class="tc">
                    <a href="https://store.goharvey.com" class="button f4 ph4 is-primary has-arrow">Start Shopping</a>
                    <p class="db cf ma3">Questions? <a href="/consultations" class="underline dim">Chat with a doctor</a></p>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
