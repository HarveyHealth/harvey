<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Get Started | Harvey</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="{{ mix('css/application.css') }}">

        <!-- Handles one-time logged-in rendering of geting-started funnel -->
        <script>
          window.Laravel = {!! $vue_data !!}
          window.$$context = 'get-started';
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

    </head>
    <body>

      @include('_includes.svgs')

      <div class="header nav header--signup" @if (Auth::guest()) :class="{'is-inverted': navIsInverted}" @endif >
          <div class="container">
              <div class="nav-left">
                  <a href="/logout" class="nav-item">
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
        !function(){var analytics=window.analytics=window.analytics||[];if(!analytics.initialize)if(analytics.invoked)window.console&&console.error&&console.error("Segment snippet included twice.");else{analytics.invoked=!0;analytics.methods=["trackSubmit","trackClick","trackLink","trackForm","pageview","identify","reset","group","track","ready","alias","debug","page","once","off","on"];analytics.factory=function(t){return function(){var e=Array.prototype.slice.call(arguments);e.unshift(t);analytics.push(e);return analytics}};for(var t=0;t<analytics.methods.length;t++){var e=analytics.methods[t];analytics[e]=analytics.factory(e)}analytics.load=function(t){var e=document.createElement("script");e.type="text/javascript";e.async=!0;e.src=("https:"===document.location.protocol?"https://":"http://")+"cdn.segment.com/analytics.js/v1/"+t+"/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(e,n)};analytics.SNIPPET_VERSION="4.0.0";
        analytics.load("LY9dLmxpEcuqz1pM6nu3g3mdXo7WpIOK");
        analytics.page();
        }}();
      </script>

      <!-- Stripe -->
      <script type="text/javascript" src="https://js.stripe.com/v2"></script>
      <script type="text/javascript" src="https://js.stripe.com/v3"></script>

      <!-- App.js -->
      <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>

    </body>
</html>
