<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Getting Started | Harvey</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="{{ mix('css/application.css') }}">

        <!-- Handles one-time logged-in rendering of geting-started funnel -->
        <script>
          window.Laravel = {!! $vue_data !!}
          window.$$context = 'getting-started';
          if (!Laravel.user.signedIn) {
            window.location.hash = '/signup';
          } else if (Laravel.user.has_an_appointment || Laravel.user.user_type !== 'patient') {
            window.location.href = '/dashboard';
          } else {
            window.location.hash = '/welcome';
          }
        </script>

        <!-- Typekit -->
        <script type="text/javascript" src="{{ mix('js/vendors/typekit.js') }}"></script>

        <script>
          !function(){var analytics=window.analytics=window.analytics||[];if(!analytics.initialize)if(analytics.invoked)window.console&&console.error&&console.error("Segment snippet included twice.");else{analytics.invoked=!0;analytics.methods=["trackSubmit","trackClick","trackLink","trackForm","pageview","identify","reset","group","track","ready","alias","debug","page","once","off","on"];analytics.factory=function(t){return function(){var e=Array.prototype.slice.call(arguments);e.unshift(t);analytics.push(e);return analytics}};for(var t=0;t<analytics.methods.length;t++){var e=analytics.methods[t];analytics[e]=analytics.factory(e)}analytics.load=function(t){var e=document.createElement("script");e.type="text/javascript";e.async=!0;e.src=("https:"===document.location.protocol?"https://":"http://")+"cdn.segment.com/analytics.js/v1/"+t+"/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(e,n)};analytics.SNIPPET_VERSION="4.0.0";
          analytics.load("LY9dLmxpEcuqz1pM6nu3g3mdXo7WpIOK");
          analytics.page();
          }}();
        </script>

        <!-- Facebook Pixel Code -->
        <script>
        // !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        // n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
        // n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
        // t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
        // document,'script','https://connect.facebook.net/en_US/fbevents.js');
        </script>
        <noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id=170447220119877&ev=PageView&noscript=1"
        /></noscript>

    </head>
    <body>

      @include('_includes.svgs')

      <div class="header nav header--signup" @if (Auth::guest()) :class="{'is-inverted': navIsInverted}" @endif >
          <div class="container">
              <div class="nav-left">
                  <a href="/" class="nav-item">
                      <div class="logo-wrapper">
                          {!! $svgImages['logo'] !!}
                      </div>
                  </a>
              </div>
              <div class="nav-right">
                  <span class="nav-item">
                      <a href="tel:800-690-9989" class="button is-primary is-outlined">(800) 690-9989</a>
                  </span>
              </div>
          </div>
      </div>

      <main class="signup-content">
        <div id="app">
          <router-view />
        </div>
      </main>

      <script>
        // (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        // (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        // m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        // })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
        // ga('create', 'UA-89414173-1', 'auto');
        // ga('send', 'pageview');
      </script>

      <!-- Stripe -->
      <script type="text/javascript" src="https://js.stripe.com/v2"></script>

      <!-- App.js -->
      <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>

    </body>
</html>
