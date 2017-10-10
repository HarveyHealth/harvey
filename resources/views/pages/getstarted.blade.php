<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, maximum-scale=1, user-scalable=0">
        <title>Get Started | Harvey</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Typography -->
        <script src="https://use.typekit.net/ukw4upn.js"></script>
        <script>try{Typekit.load({ async: true });}catch(e){}</script>
        <link rel="stylesheet" href="https://unpkg.com/gh-font-awesome@1.0.4/index.css">
        <link rel="stylesheet" href="{{ mix('css/application.css') }}">
        <script>
          window.Laravel = {!! $vue_data !!}
          // Controller has determined that the user is:
          // logged in as patient with no Appointment
          // or logged out
          var zipValidation  = localStorage.getItem('harvey_zip_validation');
          var loggedIn = Laravel.user.signedIn;
          window.$$context = 'get-started';
          if (!loggedIn && zipValidation) window.location.hash = '/signup';
          if (loggedIn && zipValidation) window.location.hash = '/welcome';
          if (!loggedIn && !zipValidation) window.location.href = '/conditions';
          if (loggedIn && !zipValidation) window.location.href = '/logout';
        </script>
    </head>
    <body>

      @include('_includes.svgs')

      <main class="height-100">
        <div id="app" class="height-100">
          <router-view />
        </div>
      </main>

      <footer>
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
      </footer>

    </body>
</html>
