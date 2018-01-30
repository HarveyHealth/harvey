@extends('legacy._layouts.public')
@section('page_title','Consultations')
@section('body_class','consultations')
@section('main_content')

<div class="sections check-load" :class="{'is-loaded': appLoaded}">

    <section id="hero-background">
        <div id="hero-video-container" class="o-70">
            <div id="video-cover"></div>
            <div class="overlay"></div>
        </div>
        <div class="container">
            <div class="tc pt6 pb4-ns">
                <h1 class="f1-l f2 lh-title mt0 mb3 ph3 white">Book a consultation with <br class="dn db-ns"/>a Naturopathic Doctor.</h1>
                <p class="f4-l f5 fw5 mb4 ph3 white">Schedule a 1-hour consultation with a licensed Naturopathic Doctor <br class="dn db-ns"/>to review lab results or build a personalized treatment plan.</p>
                <div class="tc">
                    <a href="/get-started#/signup" class="button f4-l f5 ph4 is-primary has-arrow">Check Availability</a>
                    <p class="f5-l f6 fw5 db cf ma3 white">Questions? <a href="https://telegram.me/goharvey_doctors" class="underline white dim" target="_blank">Chat free with a doctor</a></p>
                </div>
            </div>
        </div>
    </section>

    <section id="conditions">
        <div class="container">
            <p class="f2-l f3 mt5-l mt4 ph3 tc">You'll love the way you feel.</p>
            <p class="f4-l f5 pt3 pb4 ph6-l ph3 tc">By taking a personalized and whole-body approach to medicine, our integrative physicians can help you find the root cause of your health conditions and prevent risk of disease.</p>
            <div class="flex flex-wrap justify-center ma3-l">
                <div class="w-25-l w-50 mb4 tc">
                    <div class="pa4 br-100 h4 w4 dib o-80 lime-bg">
                      <img alt="" src="https://d35oe889gdmcln.cloudfront.net/assets/images/conditions/skin-issues.png" class="br-100 h3 w3 dib">
                    </div>
                    <p class="f4 pt3 fw4">Skin Issues</p>
                    <a href="https://store.goharvey.com/collections/skin-issues" class="pt2">Shop Products</a>
                </div>
                <div class="w-25-l w-50 mb4 tc">
                    <div class="pa4 br-100 h4 w4 dib o-80 pink-bg">
                      <img alt="" src="https://d35oe889gdmcln.cloudfront.net/assets/images/conditions/food-allergies.png" class="br-100 h3 w3 dib">
                    </div>
                    <p class="f4 pt3 fw4">Food Sensitivities</p>
                    <a href="https://store.goharvey.com/collections/food-allergies" class="pt2">Shop Products</a>
                </div>
                <div class="w-25-l w-50 mb4 tc">
                    <div class="pa4 br-100 h4 w4 dib o-80 brown-bg">
                      <img alt="" src="https://d35oe889gdmcln.cloudfront.net/assets/images/conditions/stress-anxiety.png" class="br-100 h3 w3 dib">
                    </div>
                    <p class="f4 pt3 fw4">Stress & Anxiety</p>
                    <a href="https://store.goharvey.com/collections/stress-anxiety" class="pt2">Shop Products</a>
                </div>
                <div class="w-25-l w-50 mb4 tc">
                    <div class="pa4 br-100 h4 w4 dib o-80 purple-bg">
                      <img alt="" src="https://d35oe889gdmcln.cloudfront.net/assets/images/conditions/digestive-issues.png" class="br-100 h3 w3 dib">
                    </div>
                    <p class="f4 pt3 fw4">Digestive Issues</p>
                    <a href="https://store.goharvey.com/collections/digestive-issues" class="pt2">Shop Products</a>
                </div>
            </div>
            <div class="flex flex-wrap justify-center ma3-l">
                <div class="w-25-l w-50 mb4 tc">
                    <div class="pa4 br-100 h4 w4 dib o-80 turquoise-bg">
                      <img alt="" src="https://d35oe889gdmcln.cloudfront.net/assets/images/conditions/fatigue.png" class="br-100 h3 w3 dib">
                    </div>
                    <p class="f4 pt3 fw4">Fatigue</p>
                    <a href="https://store.goharvey.com/collections/fatigue" class="pt2">Shop Products</a>
                </div>
                <div class="w-25-l w-50 mb4 tc">
                    <div class="pa4 br-100 h4 w4 dib o-80 slategrey-bg">
                      <img alt="" src="https://d35oe889gdmcln.cloudfront.net/assets/images/conditions/weight-loss-gain.png" class="br-100 h3 w3 dib">
                    </div>
                    <p class="f4 pt3 fw4">Weight Loss/Gain</p>
                    <a href="https://store.goharvey.com/collections/weight-loss-gain" class="pt2">Shop Products</a>
                </div>
                <div class="w-25-l w-50 mb4 tc">
                    <div class="pa4 br-100 h4 w4 dib o-80 green-bg">
                      <img alt="" src="https://d35oe889gdmcln.cloudfront.net/assets/images/conditions/womens-health.png" class="br-100 h3 w3 dib">
                    </div>
                    <p class="f4 pt3 fw4">Women's Health</p>
                    <a href="https://store.goharvey.com/collections/skin-issues" class="pt2">Shop Products</a>
                </div>
                <div class="w-25-l w-50 mb4 tc">
                    <div class="pa4 br-100 h4 w4 dib o-80 ford-bg">
                      <img alt="" src="https://d35oe889gdmcln.cloudfront.net/assets/images/conditions/general-health.png" class="br-100 h3 w3 dib">
                    </div>
                    <p class="f4 pt3 fw4">General Health</p>
                    <a href="https://store.goharvey.com/collections/general-health" class="pt2">Shop Products</a>
                </div>
            </div>
        </div>
    </section>

    <section id="featured-video" class="o-70">
        <div class="container flex-l flex-none pv4">
            <div class="w-50-l pv4-l pa3 tc">
                <a href="//www.youtube.com/watch?v=2bjmlYCDOjI&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video dim" frameborder="0" data-lity allowfullscreen>
                    <img alt="" src="https://d35oe889gdmcln.cloudfront.net/assets/images/about/amanda.jpg" class="w-100 w-60-ns br2">
                </a>
            </div>
            <div class="w-50-l pa0-ns pa3 tl-l tc">
                <div class="w-100 v-mid">
                    <p class="f2-l f3 fw5 pt3-l white">Choose your own doctor.</p>
                    <p class="f4-l f5 fw4 pt2 white">Dr. Amanda Frick, ND, is the Lead Naturopath at Harvey. She has a doctoral degree in Naturopathic Medicine and believes wellness can be achieved through a blend of traditional approaches and natural and holistic therapies.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="stories" class=" bg-near-white">
        <div class="container pb5-l pb3">
            <svg class="mt5-l mt4 ph3 tc w-100" enable-background="new 0 0 50 50" height="50px" viewBox="0 0 50 50" width="50px">
                <rect fill="none" height="50" width="50"/>
                <polygon fill="none" points="49,14 36,21 36,29   49,36 " stroke="#5F7278" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2"/>
                <path d="M36,36c0,2.209-1.791,4-4,4  H5c-2.209,0-4-1.791-4-4V14c0-2.209,1.791-4,4-4h27c2.209,0,4,1.791,4,4V36z" fill="none" stroke="#5F7278" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2"/>
            </svg>
            <p class="f2-l f3 ph3 tc">Meet Our Patients</p>
            <p class="f4-l f5 pt3 ph6-l ph5-m ph3 tc">Meet some of our patients who needed more than conventional medicine.</p>
            <div class="flex flex-wrap justify-center ph3 mt4-l mt4">
              <div class="w-40-ns w-100 pa3-l pa2 pa1 mr4-l mr3-m mr2 mb4-ns mb3 br3 ba b--light-gray bg-white tl-ns tc">
                <div class="fl w-25-l w-20-m w-100">
                    <a href="//www.youtube.com/watch?v=8e3aJIZrRNs&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video" frameborder="0" data-lity allowfullscreen>
                        <img alt="" class="fl-l mr3-ns mb3-l br-100 dim" src="https://harvey-production.s3.amazonaws.com/assets/images/consultations/danny.png">
                    </a>
                </div>
                <div class="fl w-75-l w-80-m w-100 ph3-l ph1">
                    <p class="f4-l f5 moon-gray">Danny</p>
                    <a href="//www.youtube.com/watch?v=8e3aJIZrRNs&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video" frameborder="0" data-lity allowfullscreen><i class="fa fa-play-circle f5 pt1 pr1" aria-hidden="true"></i>Watch Story</a>
                    <p class="f5-l f6 pt2 lh-title">"I found the cure to my asthma, and it had nothing to do with drugs."</p>
                </div>
              </div>
              <div class="w-40-ns w-100 pa3-l pa2 pa1 mr4-l mr3-m mr2 mb4-ns mb3 br3 ba b--light-gray bg-white tl-ns tc">
                <div class="fl w-25-l w-20-m w-100">
                    <a href="//www.youtube.com/watch?v=Cx_jr8ySOvc&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video" frameborder="0" data-lity allowfullscreen>
                        <img alt="" class="fl-l mr3-ns mb3-l br-100 dim" src="https://harvey-production.s3.amazonaws.com/assets/images/consultations/rachel.png">
                    </a>
                </div>
                <div class="fl w-75-l w-80-m w-100 ph3-l ph1">
                    <p class="f4-l f5 moon-gray">Rachel</p>
                    <a href="//www.youtube.com/watch?v=Cx_jr8ySOvc&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video" frameborder="0" data-lity allowfullscreen><i class="fa fa-play-circle f5 pt1 pr1" aria-hidden="true"></i>Watch Story</a>
                    <p class="f5-l f6 pt2 lh-title">"I have a 4-year-old daughter, and I wanted to be able to keep up with her and feel comfortable in my own skin. We did tests I didn't even know existed."</p>
                </div>
              </div>
            </div>
            <div class="flex flex-wrap justify-center ph3">
              <div class="w-40-ns w-100 pa3-l pa2 pa1 mr4-l mr3-m mr2 mb4-ns mb3 br3 ba b--light-gray bg-white tl-ns tc">
                <div class="fl w-25-l w-20-m w-100">
                    <a href="//www.youtube.com/watch?v=sSfp1F11u9U&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video" frameborder="0" data-lity allowfullscreen>
                        <img alt="" class="fl-l mr3-ns mb3-l br-100 dim" src="https://harvey-production.s3.amazonaws.com/assets/images/consultations/raffaella.png">
                    </a>
                </div>
                <div class="fl w-75-l w-80-m w-100 ph3-l ph1">
                    <p class="f4-l f5 moon-gray">Raffaella</p>
                    <a href="//www.youtube.com/watch?v=sSfp1F11u9U&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video" frameborder="0" data-lity allowfullscreen><i class="fa fa-play-circle f5 pt1 pr1" aria-hidden="true"></i>Watch Story</a>
                    <p class="f5-l f6 pt2 lh-title">"It turned out I had leaky gut and multiple food allergies. I had to cut out a lot of foods, which I was later able to re-introduce. I feel great."</p>
                </div>
              </div>
              <div class="w-40-ns w-100 pa3-l pa2 pa1 mr4-l mr3-m mr2 mb4-ns mb3 br3 ba b--light-gray bg-white tl-ns tc">
                <div class="fl w-25-l w-20-m w-100">
                    <a href="//www.youtube.com/watch?v=sO9lHH08sVs&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video" frameborder="0" data-lity allowfullscreen>
                        <img alt="" class="fl-l mr3-ns mb3-l br-100 dim" src="https://harvey-production.s3.amazonaws.com/assets/images/consultations/steve.png">
                    </a>
                </div>
                <div class="fl w-75-l w-80-m w-100 ph3-l ph1">
                    <p class="f4-l f5 moon-gray">Steve</p>
                    <a href="//www.youtube.com/watch?v=sO9lHH08sVs&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video" frameborder="0" data-lity allowfullscreen><i class="fa fa-play-circle f5 pt1 pr1" aria-hidden="true"></i>Watch Story</a>
                    <p class="f5-l f6 pt2 lh-title">"We did some blood-work, and it was a great experience. She recommended a vitamin regimen that improved my performance and recovery time."</p>
                </div>
              </div>
            </div>
            <div class="flex flex-wrap justify-center ph3">
              <div class="w-40-ns w-100 pa3-l pa2 pa1 mr4-l mr3-m mr2 mb4-ns mb3 br3 ba b--light-gray bg-white tl-ns tc">
                <div class="fl w-25-l w-20-m w-100">
                    <a href="//www.youtube.com/watch?v=bbbxHViNiLk&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video" frameborder="0" data-lity allowfullscreen>
                        <img alt="" class="fl-l mr3-ns mb3-l br-100 dim" src="https://harvey-production.s3.amazonaws.com/assets/images/consultations/sukari.png">
                    </a>
                </div>
                <div class="fl w-75-l w-80-m w-100 ph3-l ph1">
                    <p class="f4-l f5 moon-gray">Sukari</p>
                    <a href="//www.youtube.com/watch?v=bbbxHViNiLk&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video" frameborder="0" data-lity allowfullscreen><i class="fa fa-play-circle f5 pt1 pr1" aria-hidden="true"></i>Watch Story</a>
                    <p class="f5-l f6 pt2 lh-title">"The food allergies test was especially helpful because it showed I had sensitivities to dairy, which I was eating every day. I was able to make substitutions to better support my skin and gut health."</p>
                </div>
              </div>
              <div class="w-40-ns w-100 pa3-l pa2 pa1 mr4-l mr3-m mr2 mb4-ns mb3 br3 ba b--light-gray bg-white tl-ns tc">
                <div class="fl w-25-l w-20-m w-100">
                    <a href="//www.youtube.com/watch?v=_lAsiO_wZ3U&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video" frameborder="0" data-lity allowfullscreen>
                        <img alt="" class="fl-l mr3-ns mb3-l br-100 dim" src="https://harvey-production.s3.amazonaws.com/assets/images/consultations/jonathan.png">
                    </a>
                </div>
                <div class="fl w-75-l w-80-m w-100 ph3-l ph1">
                    <p class="f4-l f5 moon-gray">Jonathan</p>
                    <a href="//www.youtube.com/watch?v=_lAsiO_wZ3U&rel=0&modestbranding=0&autohide=1&showinfo=0&VQ=HD720" class="watch-video" frameborder="0" data-lity allowfullscreen><i class="fa fa-play-circle f5 pt1 pr1" aria-hidden="true"></i>Watch Story</a>
                    <p class="f5-l f6 pt2 lh-title">"I did a CT scan with my regular doctor, and they gave me medicine, but none of it worked. Luckily I found Dr Frick, and we found gut bacteria through testing that I never would have found otherwise."</p>
                </div>
              </div>
            </div>
        </div>
    </section>

    <section class="section is-paddingless is-marginless" id="social-feed">
        <ul class="juicer-feed" data-feed-id="goharveyapp" data-per="15"></ul>
    </section>

    <section id="pricing">
        <div class="container">
            <div class="tc pt5-l pt5 pa4">
                <p class="f3 moon-gray">Let's talk about pricing.</p>
            </div>
            <div class="flex flex-wrap pa3">
                <div class="w-50-l w-100">
                    <div class="h-100-l h-75 dt w-100 pr3-l tl-l tc">
                        <div class="dtc v-mid ph3-l">
                            <p class="f4-l p5 fw6 pb2 lh-title">How much is a consultation?</p>
                            <p class="f5-l f6 mb3">Full/first-time consultations are $150, lasting 60-90 minutes. Follow-up consults are $75, lasting 15-30 minutes.</p>
                            <p class="f4-l p5 fw6 pt2 pb2 lh-title">Are your services eligible for HSA/FSA?</p>
                            <p class="f5-l f6 mb3">Yes. While we are not yet contracted with any insurance providers, all Harvey services can be purchased using your HSA/FSA funds.</p>
                            <p class="f4-l p5 fw6 pt2 pb2 lh-title">Can I buy lab tests and supplements on the <br class="dn db-l"/>store without a consultation?</p>
                            <p class="f5-l f6 mb4">Yes. While we strongly recommend having a full consultation before buying lab tests or supplements, you can <a href="https://store.goharvey.com">shop our store</a> at any time and our doctors are always available via chat.</p>
                        </div>
                    </div>
                </div>
                <div class="w-50-l w-100">
                    <div class="h-100-l h-75 dt w-100">
                        <figure class="dtc v-mid pt3-l pb4 tc">
                            <img alt="" src="https://harvey-production.s3.amazonaws.com/assets/images/consultations/dual-pricing.png" alt="">
                        </figure>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="get-started" class="bg-near-white">
        <div class="container">
            <div class="ph6-l ph4-m ph3 pv5 tc">
                <p class="f2-l f3">Serving patients across 40 states.</p>
                <p class="f4-l f5 pa3">We recommend talking with a doctor to receive a full treatment plan. You can book a video consultation today or start a private chat with one of our doctors on Telegram.</p>
                <div class="tc">
                    <a href="/get-started#/signup" class="button f4-ns f5 ph4 is-primary has-arrow">Check Availability</a>
                    <p class="f5-l f6 fw5 db cf ma3">Questions? <a href="https://telegram.me/goharvey_doctors" class="underline dim" target="_blank">Chat free with a doctor</a></p>
                </div>
            </div>
        </div>
    </section>

</div>

@endsection
