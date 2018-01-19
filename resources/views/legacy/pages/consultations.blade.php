@extends('legacy._layouts.public')
@section('page_title','Consultations')
@section('body_class','home')
@section('main_content')

<div class="sections check-load" :class="{'is-loaded': appLoaded}">

    <section id="hero-background">
        <div id="hero-video-container">
            <video id="hero-video" autoplay loop muted></video>
            <div id="video-cover"></div>
            <div id="overlay"></div>
        </div>
        <div class="container">
            <div class="tc pt6-ns pb4-ns ph7-ns">
                <h1 class="f1 lh-title mt0 white">Book a consultation with a Naturopathic Doctor.</h1>
                <p class="subtitle f4 fw4 white">Schedule a 1-hour consultation with a licensed Naturopathic Doctor to review lab results or build a personalized treatment plan.</p>
                <div class="tc">
                    <a href="/get-started" class="button f4 ph4 is-primary has-arrow">Check Availability</a>
                    <p class="db cf ma3 white">Questions? <a href="https://telegram.me/goharvey_doctors" class="underline white dim">Chat free with a doctor</a></p>
                </div>
            </div>
        </div>
    </section>

    <section id="conditions">
        <div class="container">
            <p class="f2 mt5 tc">You will love the way you feel.</p>
            <p class="f4 pt3 ph6-ns tc">By taking a personalized and whole-body approach to medicine, our integrative physicians can help you find the root cause of many common health conditions and prevent risk of disease.</p>
            <div class="flex-l ma3 mt5">
                <div class="w-25 tc">
                    <div class="pa4 br-100 h4 w4 dib o-80 lime-bg">
                      <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/conditions/skin-issues.png" class="br-100 h3 w3 dib">
                    </div>
                    <p class="f4 pt3 fw4">Skin Issues</p>
                    <a href="https://store.goharvey.com/collections/skin-issues" class="pt2">Shop Products</a>
                </div>
                <div class="w-25 tc">
                    <div class="pa4 br-100 h4 w4 dib o-80 pink-bg">
                      <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/conditions/food-allergies.png" class="br-100 h3 w3 dib">
                    </div>
                    <p class="f4 pt3 fw4">Food Allergies</p>
                    <a href="https://store.goharvey.com/collections/food-allergies" class="pt2">Shop Products</a>
                </div>
                <div class="w-25 tc">
                    <div class="pa4 br-100 h4 w4 dib o-80 brown-bg">
                      <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/conditions/stress-anxiety.png" class="br-100 h3 w3 dib">
                    </div>
                    <p class="f4 pt3 fw4">Stess & Anxiety</p>
                    <a href="https://store.goharvey.com/collections/stress-anxiety" class="pt2">Shop Products</a>
                </div>
                <div class="w-25 tc">
                    <div class="pa4 br-100 h4 w4 dib o-80 purple-bg">
                      <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/conditions/digestive-issues.png" class="br-100 h3 w3 dib">
                    </div>
                    <p class="f4 pt3 fw4">Digestive Issues</p>
                    <a href="https://store.goharvey.com/collections/digestive-issues" class="pt2">Shop Products</a>
                </div>
            </div>
            <div class="flex-l ma3 mt4">
                <div class="w-25 tc">
                    <div class="pa4 br-100 h4 w4 dib o-80 turquoise-bg">
                      <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/conditions/fatigue.png" class="br-100 h3 w3 dib">
                    </div>
                    <p class="f4 pt3 fw4">Fatigue</p>
                    <a href="https://store.goharvey.com/collections/fatigue" class="pt2">Shop Products</a>
                </div>
                <div class="w-25 tc">
                    <div class="pa4 br-100 h4 w4 dib o-80 slategrey-bg">
                      <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/conditions/weight-loss-gain.png" class="br-100 h3 w3 dib">
                    </div>
                    <p class="f4 pt3 fw4">Weight Loss/Gain</p>
                    <a href="https://store.goharvey.com/collections/weight-loss-gain" class="pt2">Shop Products</a>
                </div>
                <div class="w-25 tc">
                    <div class="pa4 br-100 h4 w4 dib o-80 green-bg">
                      <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/conditions/womens-health.png" class="br-100 h3 w3 dib">
                    </div>
                    <p class="f4 pt3 fw4">Women's Health</p>
                    <a href="https://store.goharvey.com/collections/skin-issues" class="pt2">Shop Products</a>
                </div>
                <div class="w-25 tc">
                    <div class="pa4 br-100 h4 w4 dib o-80 ford-bg">
                      <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/conditions/general-health.png" class="br-100 h3 w3 dib">
                    </div>
                    <p class="f4 pt3 fw4">General Health</p>
                    <a href="https://store.goharvey.com/collections/general-health" class="pt2">Shop Products</a>
                </div>
            </div>
        </div>
    </section>

    <section id="featured-video" class="o-70 mt5">
        <div class="container flex-l pv4">
            <div class="w-50-l pv4 tc">
                <a href="//www.youtube.com/watch?v=2bjmlYCDOjI&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video" frameborder="0" data-lity allowfullscreen>
                    <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/about/amanda.jpg" class="w-100 w-60-ns br2">
                </a>
            </div>
            <div class="w-50-l pt4">
                <div class="w-100 center v-mid">
                    <p class="f2 pt2 white">Choose your own doctor.</p>
                    <p class="f4 pt white">Dr. Amanda Frick, ND is a practicing naturopathic physician with a doctoral degree in Naturopathic Medicine. She believes wellness can be achieved through a blend of traditional approaches as well as natural and holistic therapies.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="patients" class="pt2 pb5 bg-near-white">
        <div class="container">
            <svg class="mt5 tc w-100" enable-background="new 0 0 50 50" height="50px" id="Layer_1" version="1.1" viewBox="0 0 50 50" width="50px">
                <rect fill="none" height="50" width="50"/>
                <polygon fill="none" points="49,14 36,21 36,29   49,36 " stroke="#5F7278" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2"/>
                <path d="M36,36c0,2.209-1.791,4-4,4  H5c-2.209,0-4-1.791-4-4V14c0-2.209,1.791-4,4-4h27c2.209,0,4,1.791,4,4V36z" fill="none" stroke="#5F7278" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2"/>
            </svg>
            <p class="f2 tc">Meet Our Patients</p>
            <p class="f4 pt3 ph6-ns tc">Many of our patients were failed by the traditional medical system.</p>
            <div class="flex ph4 mt5 mb4">
              <div class="w-50 pa3 mr4 br3 ba b--light-gray bg-white">
                <a href="//www.youtube.com/watch?v=8e3aJIZrRNs&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video" frameborder="0" data-lity allowfullscreen>
                    <img class="fl-l w-25 mr4-ns br-100 dim" src="http://harvey-production.s3.amazonaws.com/assets/images/consultations/danny.png">
                </a>
                <p class="f5 pt2">"I found the cure to my asthma, and it had nothing to do with drugs."</p>
                <p class="f5 pt2 moon-gray">Danny, Los Angeles</p>
              </div>
              <div class="w-50 pa3 mr4 br3 ba b--light-gray bg-white">
                <a href="//www.youtube.com/watch?v=Cx_jr8ySOvc&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video" frameborder="0" data-lity allowfullscreen>
                    <img class="fl-l w-25 mr4-ns br-100 dim" src="http://harvey-production.s3.amazonaws.com/assets/images/consultations/rachel.png">
                </a>
                <p class="f5 pt2">"I have a 4-year-old daughter, and I wanted to be able to keep up with her and finally feel comfortable in my own skin. We did lab tests I didn't even know existed."</p>
                <p class="f5 pt2 moon-gray">Rachel, Los Angeles</p>
              </div>
            </div>
            <div class="flex ph4 mb4">
              <div class="w-50 pa3 mr4 br3 ba b--light-gray bg-white">
                <a href="//www.youtube.com/watch?v=sSfp1F11u9U&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video" frameborder="0" data-lity allowfullscreen>
                    <img class="fl-l w-25 mr4-ns br-100 dim" src="http://harvey-production.s3.amazonaws.com/assets/images/consultations/raffaella.png">
                </a>
                <p class="f5 pt2">"Turned out I had leaky gut and multiple food allergies. I had to cut some foods out of my diet, which I was later able to re-introduce. I now feel great."</p>
                <p class="f5 pt2 moon-gray">Raffaella, Los Angeles</p>
              </div>
              <div class="w-50 pa3 mr4 br3 ba b--light-gray bg-white">
                <a href="//www.youtube.com/watch?v=sO9lHH08sVs&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video" frameborder="0" data-lity allowfullscreen>
                    <img class="fl-l w-25 mr4-ns br-100 dim" src="http://harvey-production.s3.amazonaws.com/assets/images/consultations/steve.png">
                </a>
                <p class="f5 pt2">"We did some bloodwork, and it was an amazing experience. She recommended a vitamin regimen that improved my performance and recovery time."</p>
                <p class="f5 pt2 moon-gray">Steve, Los Angeles</p>
              </div>
            </div>
        </div>
    </section>

    <section id="pricing">
        <div class="container">
            <div class="tc pt5 ph7">
                <p class="f3 moon-gray">Let's talk about pricing.</p>
            </div>
            <div class="flex ph4 mb3">
                <div class="w-70">
                    <div class="vh-75 dt w-100 pr5-ns">
                        <div class="dtc v-mid ph3 ph4-l">
                            <p class="f3 fw5 pb2">Do you take insurance?</p>
                            <p class="f4 mb3">Preventative healthcare expenditures and specialty lab tests are not typically reimbursable under most health plans. However, you may be able to use an HSA/FSA account to pay for our services.</p>
                            <p class="f3 fw5 pb2">How much are lab tests?</p>
                            <p class="f4 mb3">While most clinics mark up the prices of their speciality lab tests, we sell them at wholesale cost. Lab tests range from $99 to $320.</p>
                            <p class="f3 fw5 pb2">How long are consultations?</p>
                            <p class="f4 mb3">The initial consultation is 60 minutes for $150. Follow-ups to review lab results are typically 30 minutes for $75.</p>
                        </div>
                    </div>
                </div>
                <div class="w-30">
                    <div class="vh-75 dt w-100">
                        <figure class="dtc v-mid">
                            <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/home/pricing.png" alt="">
                        </figure>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="get-started" class="bg-near-white">
        <div class="container">
            <div class="tc pv5 ph6-ns">
                <p class="f2">Serving patients in 45 states.</p>
                <p class="f4 pa4">We recommend talking with an integrative doctor to receive a full treatment plan. You can book a video consultation today or private chat with our doctors on Telegram.</p>
                <div class="tc">
                    <a href="/get-started" class="button f4 ph4 is-primary has-arrow">Check Availability</a>
                    <p class="db cf ma3">Questions? <a href="https://telegram.me/goharvey_doctors" class="underline dim">Chat free with a doctor</a></p>
                </div>
            </div>
        </div>
    </section>

</div>

@endsection
