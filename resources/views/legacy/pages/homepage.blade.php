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
            <div class="tc pt6 pb4-ns ph2">
                <h1 class="f1-l f2 lh-title mt0 mb3 white">Learn what your body really <br class="dn db-ns"/>needs to feel its best.</h1>
                <p class="f4-l f5 fw5 mb4 white">Harvey offers advanced in-home lab tests and professional-grade <br class="dn db-l"/> supplements—under the guidance of integrative doctors.</p>
                <div class="tc">
                    <a href="https://store.goharvey.com" class="button f4-l f5 ph4 is-primary has-arrow">Start Shopping</a>
                    <p class="f5-l f6 fw5 db cf ma3 white">Questions? <a href="/consultations" class="underline white dim">Consult a doctor</a></p>
                </div>
            </div>
        </div>
    </section>

    <section id="products">
        <div class="container">
            <div class="flex-ns flex-none pv5-ns pv4">
                <div class="w-third-ns w-100 tc">
                    <div class="pa4 br-100 dib o-80 brown-bg">
                        <span class="icon icon_steps_2 h60 w60 dib"></span>
                    </div>
                    <div class="pa2">
                        <p class="f3-l f4 fw5 pt2 lh-title">Home Lab Tests</p>
                        <p class="f5-l f6 pa3-l pa2">Order one of our specialized home lab tests, such as <a href="https://store.goharvey.com/products/food-allergy-lab-test">Food Sensitivities</a>, <a href="https://store.goharvey.com/products/micronutrient-lab-test">Micronutrient</a>, <a href="https://store.goharvey.com/products/microbiome-lab-test">Microbiome</a>, and many more.</p>
                        <a href="https://store.goharvey.com/collections/lab-tests" class="f5 fw5 ba ph3 pv2 mb3 dib br2 mt2 dim"><i class="fa fa-shopping-cart pr1" aria-hidden="true"></i> Shop Now</a>
                    </div>
                </div>
                <div class="w-third-ns w-100 tc">
                    <div class="pa4 br-100 dib o-80 brown-bg">
                      <span class="icon icon_steps_4 h60 w60 dib"></span>
                    </div>
                    <div class="pa2">
                        <p class="f3-l f4 fw5 pt2 lh-title">Vitamins & Supplements</p>
                        <p class="f5-l f6 pa3-l pa2">Shop the largest online dispensary of professional-grade vitamins and supplements at near wholesale prices.</p>
                        <a href="https://store.goharvey.com/collections/supplements" class="f5 fw5 ba ph3 pv2 mb3 dib br2 mt2 dim"><i class="fa fa-shopping-cart pr1" aria-hidden="true"></i> Shop Now</a>
                    </div>
                </div>
                <div class="w-third-ns w-100 tc">
                    <div class="pa4 br-100 dib o-80 brown-bg">
                      <span class="icon icon_steps_1 h60 w60 dib"></span>
                    </div>
                    <div class="pa2">
                        <p class="f3-l f4 fw5 pt2 lh-title">Holistic Treatment Plans</p>
                        <p class="f5-l f6 pa3-l pa2">Chat for free with a licensed Naturopathic Doctor about your health goals, or schedule a full consultation to build a treatment plan.</p>
                        <a href="/consultations" class="f5 fw5 ba ph3 pv2 mb3 dib br2 mt2 dim"><i class="fa fa-stethoscope pr1" aria-hidden="true"></i> Meet Doctors</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="featured-video" class="o-70">
        <div class="container flex-l flex-none pv4">
            <div class="w-50-l pv4-l pa3 tc">
                <a href="//www.youtube.com/watch?v=nfyk7irbYsw&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video dim" frameborder="0" data-lity allowfullscreen>
                    <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/home/nutrition.jpg" class="w-100 w-60-ns br2">
                </a>
            </div>
            <div class="w-50-l pt3-l pa3 tl-l tc">
                <div class="w-100 v-mid">
                    <p class="f3-l f4 fw5 pt3-l white">#1 Natural Health Marketplace</p>
                    <p class="f4-l f5 fw4 pt2 white">Harvey empowers people to find natural treatments to chronic health conditions. We believe in science, and we believe in treating the whole body, not just symptoms.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="services" style="background-image: url(https://harvey-production.s3.amazonaws.com/assets/images/home/symbols.png)">
        <div class="container">
            <div class="tc pt5-l pt4 ph3 ph6-l">
                <p class="f2-l f3">Private & Personalized Treatment</p>
                <p class="f4-l f5 pv3 ph4-l">Your health shouldn't be a guessing game of online searches. Our doctors have successfully treated thousands of patients fighting skin issues, food allergies, stress and anxiety, digestive issues, fatigue, weight gain/loss, hormonal changes, and many other health conditions.</p>
                <div class="db cf ma2 f4">
                    <a href="/consultations#stories"><i class="fa fa-play-circle pt1" aria-hidden="true"></i> Watch Patient Stories</a>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="flex-ns flex-wrap-ns pt4 pb5-l pb3">
                <div class="w-100 w-50-ns tc">
                    <div class="ph5-l ph3 mb4">
                      <i class="fa fa-pagelines f2-l f3 ma1" aria-hidden="true"></i>
                      <p class="f3-l f4 fw3 mv2">Naturopathic Doctors</p>
                      <p class="f4-l f5">All of our doctors graduated from four-year residential medical school, passed a national board exam and have a state medical license.</p>
                    </div>
                </div>
                <div class="w-100 w-50-ns tc">
                    <div class="ph5-l ph3 mb4">
                      <i class="fa fa-balance-scale f2-l f3 ma1" aria-hidden="true"></i>
                      <p class="f3-l f4 fw3 mv2">Alternative Approach</p>
                      <p class="f4-l f5">Harvey doctors take a whole-body, root-cause approach to medicine. We view the body as one integrated system, not a collection of organs.</p>
                    </div>
                </div>
                <div class="w-100 w-50-ns tc">
                    <div class="ph5-l ph3 mb4">
                      <i class="fa fa-flask f2-l f3 ma1" aria-hidden="true"></i>
                      <p class="f3-l f4 fw3 mv2">Advanced Lab Testing</p>
                      <p class="f4-l f5">We offer specialized home lab tests you can't get at your primary doctor. Most lab tests you can take from the comfort of your home.</p>
                    </div>
                </div>
                <div class="w-100 w-50-ns tc">
                    <div class="ph5-l ph3 mb4">
                      <i class="fa fa-cutlery f2-l f3 ma1" aria-hidden="true"></i>
                      <p class="f3-l f4 fw3 mv2">Nutritional Guidance</p>
                      <p class="f4-l f5">Our natural treatment plans may include supplements, or diet and lifestyle changes. But we will never prescribe pharmaceuticals.</p>
                    </div>
                </div>
                <div class="w-100 w-50-ns tc">
                    <div class="ph5-l ph3 mb4">
                      <i class="fa fa-desktop f2-l f3 ma1" aria-hidden="true"></i>
                      <p class="f3-l f4 fw3 mv2">Accessable Records</p>
                      <p class="f4-l f5">Manage all your appointments, lab results, supplement prescriptions, and treatment plans from one central online dashboard.</p>
                    </div>
                </div>
                <div class="w-100 w-50-ns tc">
                    <div class="ph5-l ph3 mb4">
                      <i class="fa fa-comments-o f2-l f3 ma1" aria-hidden="true"></i>
                      <p class="f3-l f4 fw3 mv2">Free Messaging</p>
                      <p class="f4-l f5">We offer unlimited free chat with your specialist doctor through a private channel. Full video consultations are $150.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="press" class="bt b--near-white">
        <div class="container">
            <div class="tc pt4 ph3">
                <p class="f3-l f4 moon-gray">Don't just take our word for it.</p>
            </div>
            <div class="flex flex-wrap pa4">
                <div class="ph5-l ph4-m ph3 pv3-l pv2 w-25-ns w-50 tc">
                    <a href="https://www.youtube.com/watch?v=Q9usyL_Zs-A" class="o-70" target="_blank">
                        <img src="https://harvey-production.s3.amazonaws.com/assets/images/home/press/quality-talks.png">
                    </a>
                </div>
                <div class="ph5-l ph4-m ph3 pv3-l pv2 w-25-ns w-50 tc">
                    <a href="https://www.cnbc.com/2017/10/11/homehero-pivots-rebrands-to-harvey.html" class="o-70" target="_blank">
                        <img src="https://harvey-production.s3.amazonaws.com/assets/images/home/press/cnbc.png">
                    </a>
                </div>
                <div class="ph5-l ph4-m ph3 pv3-l pv2 w-25-ns w-50 tc">
                    <a href="https://techcrunch.com/2017/12/01/a-prescription-for-healing-the-healthcare-industry/" class="o-70" target="_blank">
                        <img src="https://harvey-production.s3.amazonaws.com/assets/images/home/press/techcrunch.png">
                    </a>
                </div>
                <div class="ph5-l ph4-m ph3 pv3-l pv2 w-25-ns w-50 tc">
                    <a href="https://www.wellandgood.com/good-food/health-benefits-shatavari/" class="o-70" target="_blank">
                        <img src="https://harvey-production.s3.amazonaws.com/assets/images/home/press/well-and-good.png">
                    </a>
                </div>
                <div class="ph5-l ph4-m ph3 pv3-l pv2 w-25-ns w-50 tc">
                    <a href="http://www.adweek.com/digital/5-workplace-tech-tools-every-office-should-use-in-2018/" class="o-70" target="_blank">
                        <img src="https://harvey-production.s3.amazonaws.com/assets/images/home/press/adweek.png">
                    </a>
                </div>
                <div class="ph5-l ph4-m ph3 pv3-l pv2 w-25-ns w-50 tc">
                    <a href="https://www.youtube.com/watch?v=RL9LxmEcUOM" class="o-70" target="_blank">
                        <img src="https://harvey-production.s3.amazonaws.com/assets/images/home/press/ted.png">
                    </a>
                </div>
                <div class="ph5-l ph4-m ph3 pv3-l pv2 w-25-ns w-50 tc">
                    <a href="https://www.bustle.com/p/10-things-your-tongue-can-tell-you-about-your-health-based-on-chinese-medicine-7544613" class="o-70" target="_blank">
                        <img src="https://harvey-production.s3.amazonaws.com/assets/images/home/press/bustle.png">
                    </a>
                </div>
                <div class="ph5-l ph4-m ph3 pv3-l pv2 w-25-ns w-50 tc">
                    <a href="http://www.organicauthority.com/curious-about-acupuncture-but-scared-of-needles-check-out-these-new-techniques-that-celebs-love" class="o-70" target="_blank">
                        <img src="https://harvey-production.s3.amazonaws.com/assets/images/home/press/organic-authority.png">
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
            <div class="ph6-l ph4-m ph3 pv5 tc">
                <p class="f2-l f3">Health brands hand-picked by doctors.</p>
                <p class="f4-l f5 pa3">If you're new to integrative medicine, we recommend chatting with one of our doctors before buying lab tests or supplements. If you know what you need, you can start shopping now.</p>
                <div class="tc">
                    <a href="https://store.goharvey.com" class="button f4-ns f5 ph4 is-primary has-arrow">Start Shopping</a>
                    <p class="f5-l f6 fw5 db cf ma3">Questions? <a href="/consultations" class="underline dim">Consult a doctor</a></p>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
