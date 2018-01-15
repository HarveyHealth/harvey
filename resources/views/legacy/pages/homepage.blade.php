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
        <div class="container">
            <div class="tc pt5-ns pb4-ns ph7-ns">
                <h1 class="title is-1">Learn what your body really needs to feel its best.</h1>
                <p class="subtitle is-5">Harvey offers preventative lab tests and clinical-grade vitamins and supplements—under the guidance of integrative doctors.</p>
                <div class="tc">
                    <a href="https://store.goharvey.com" class="button is-primary is-medium has-arrow">Start Shopping</a>
                    <p class="db cf ma3 white">Questions? <a href="/health-chat" class="underline white dim">Chat with a doctor</a></p>
                </div>
            </div>
        </div>
    </section>

    <section id="products">
        <div class="container tc pv5">
            <div class="columns">
                <div class="column">
                    <figure class="icon-wrapper icon-wrapper-has-background is-gray">
                        <span class="icon icon_steps_2"></span>
                    </figure>
                    <div class="pt2">
                        <p class="font-xl"><strong>Home Lab Tests</strong></p>
                        <p class="font-md pt2">Order one of our specialized lab tests, such as <a href="https://store.goharvey.com/products/food-allergy-lab-test">Food Allergy</a>, <a href="https://store.goharvey.com/products/micronutrient-lab-test">Micronutrient</a>, <a href="https://store.goharvey.com/products/hormones-lab-test">Hormones</a>, <a href="https://store.goharvey.com/products/toxic-metals-lab-test">Toxic Metals</a>, <a href="https://store.goharvey.com/products/microbiome-lab-test">Microbiome</a>, and many more.</p>
                        <a href="https://store.goharvey.com/collections/lab-tests" class="f5 ba ph3 pv2 mb2 dib br2 mt3 dim"><i class="fa fa-shopping-cart pr1" aria-hidden="true"></i> Shop Now</a>
                    </div>
                </div>
                <div class="column">
                    <figure class="icon-wrapper icon-wrapper-has-background is-gray">
                        <span class="icon icon_steps_4"></span>
                    </figure>
                    <div class="pt2">
                        <p class="font-xl"><strong>Vitamins & Supplements</strong></p>
                        <p class="font-md pt2">Shop the largest online dispensory of professional-grade vitamins and supplements at guaranteed wholesale prices.</p>
                        <a href="https://store.goharvey.com/collections/supplements" class="f5 ba ph3 pv2 mb2 dib br2 mt3 dim"><i class="fa fa-shopping-cart pr1" aria-hidden="true"></i> Shop Now</a>
                    </div>
                </div>
                <div class="column">
                    <figure class="icon-wrapper icon-wrapper-has-background is-gray">
                        <span class="icon icon_steps_1"></span>
                    </figure>
                    <div class="pt2">
                        <p class="font-xl"><strong>Functional Doctors</strong></p>
                        <p class="font-md pt2">Chat for free with our licensed Naturopathic Doctors about your health goals, or schedule a full consultation to build a detailed treatment plan.</p>
                        <a href="" class="f5 ba ph3 pv2 mb2 dib br2 mt3 dim"><i class="fa fa-comments-o pr1" aria-hidden="true"></i> Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="testimonials" class="">
        <div class="container pt4 flex-l">
            <div class="w-50-l pv4 tc">
                <a href="//www.youtube.com/watch?v=2bjmlYCDOjI&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video" frameborder="0" data-lity allowfullscreen>
                    <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/about/amanda.jpg" class="w-100 w-70-ns br2">
                </a>
            </div>
            <div class="w-50-l pt4">
                <div class="w-100 white center">
                    <p class="f2">Start your health journey.</p>
                    <p class="f4">Harvey empowers people to find natural treatments to chronic health conditions. We believe in science, and we believe in treating the whole body, not just a set of symptoms.</p>
                    <a href="" class="f4 ba ph3 pv2 mb2 dib br2 mt3 white dim"><i class="fa fa-youtube pt1 pr1" aria-hidden="true"></i> Patient Stories</a>
                </div>
            </div>
        </div>
        <div class="container flex-l">
            <div class="flex-ns flex-wrap-ns pb4">
              <div class="w-100 w-50-ns tc">
                <div class="ph5 mb4">
                  <i class="fa fa-pagelines f2 white" aria-hidden="true"></i>
                  <p class="f3 mv2 white">Naturopathic Doctors</p>
                  <p class="f4 white">All of our doctors graduated from four-year residential medical school, passed a national board exam and have a state medical license.</p>
                </div>
              </div>
              <div class="w-100 w-50-ns tc">
                <div class="ph5 mb4">
                  <i class="fa fa-balance-scale f2 white" aria-hidden="true"></i>
                  <p class="f3 mv2 white">Integrative Approach</p>
                  <p class="f4 white">Harvey doctors take whole-body, root-cause approach to medicine. We view the body as one integrated system, not a collection of organs.</p>
                </div>
              </div>

              <div class="w-100 w-50-ns tc">
                <div class="ph5 mb4">
                  <i class="fa fa-flask f2 white" aria-hidden="true"></i>
                  <p class="f3 mv2 white">Advanced Lab Testing</p>
                  <p class="f4 white">We offer specialized home lab tests you can't get at your primary doctor. Most lab tests you can take from the comfort of your home.</p>
                </div>
              </div>
              <div class="w-100 w-50-ns tc">
                <div class="ph5 mb4">
                  <i class="fa fa-cutlery f2 white" aria-hidden="true"></i>
                  <p class="f3 mv2 white">Nutritional Guidance</p>
                  <p class="f4 white">Our natural treatment plans may include vitamins, supplements, or diet and lifestyle changes. We will never prescribe pharmaceuticals.</p>
                </div>
              </div>
            </div>
        </div>
    </section>

    <section id="records" class="bg-white">
        <div class="container">
            <div class="tc pt5 pb4 ph6">
                <p class="f1">Everything in one place.</p>
                <p class="f3 pa3">Access lab results, supplement prescriptions and treatment plans all in one place, with unlimited <strong>free messaging</strong> with your doctor.</p>
            </div>
        </div>
        <div style="
            background-image: url(http://harvey-production.s3.amazonaws.com/assets/images/home/lab-records.png);
            background-attachment: fixed; background-position: 100% 80%;"
            class="cover h4 w-100 vh-25 o-50">
    </section>

<!--     <div class="container">
        <div class="bb b--light-gray"></div>
    </div> -->

    <section id="press">
        <div class="container">
            <div class="tc pt4 ph7">
                <p class="f4 moon-gray">Don't take our word for it.</p>
            </div>
            <div class="flex flex-wrap pa4">
                <div class="ph3 ph5-ns pv3 w-50 w-33-m w-25-ns tc">
                    <a href="http://google.com" class="dim">
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
        </div>
    </section>

    <section id="get-started" class="bg-near-white">
        <div class="container">
            <div class="tc pv5 ph7">
                <p class="f2">Start your health journey.</p>
                <p class="f4 pa3">Harvey offers preventative lab tests and clinical-grade vitamins and supplements—under the guidance of integrative doctors.</p>
                <div class="tc">
                    <a href="https://store.goharvey.com" class="button is-primary is-medium has-arrow">Start Shopping</a>
                    <p class="db cf ma3">Questions? <a href="/health-chat" class="underline dim">Chat with a doctor</a></p>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
