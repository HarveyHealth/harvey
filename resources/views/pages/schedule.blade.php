<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Book Appointment | Harvey</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/application.css') }}">

        <!-- Typekit -->
        <script type="text/javascript" src="{{ mix('js/vendors/typekit.js') }}"></script>

        {{-- Tracking scripts (GA, Pixel, mixpanel) load with vue-multianalytics --}}
        @if (App::environment('local'))
          <script type="text/javascript" src="{{ mix('js/vendors/intercom.js') }}"></script>
        @endif

        <!-- Facebook Pixel Code -->
        <script>
        !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
        n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
        document,'script','https://connect.facebook.net/en_US/fbevents.js');
        </script>
        <noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id=170447220119877&ev=PageView&noscript=1"
        /></noscript>
        <!-- DO NOT MODIFY -->
        <!-- End Facebook Pixel Code -->
    </head>
    <body>

      <!-- Defining svgs for the document -->
      <svg xmlns="http://www.w3.org/2000/svg" style="display:none;">
        <symbol id="harvey-logo" viewBox="0 0 185 48">
          <g fill="none" fill-rule="evenodd">
            <path d="M49.348 21.777h11.707V11.16h4.095V38.3h-4.095V25.626H49.348V38.3h-4.096V11.16h4.096v10.617zm38.63 9.93h-11.62L73.336 38.3h-4.412l13.43-28.845L95.308 38.3h-4.483l-2.847-6.592zm-1.67-3.848l-4.026-9.23-4.218 9.23h8.244zm21.966-1.126l8.402 11.566h-5.01l-7.752-11.11h-.738V38.3H99.08V11.16h4.8c3.585 0 6.175.673 7.77 2.02 1.756 1.5 2.635 3.482 2.635 5.942 0 1.922-.55 3.575-1.652 4.958-1.102 1.382-2.555 2.267-4.36 2.654zm-5.098-3.112h1.3c3.88 0 5.82-1.482 5.82-4.447 0-2.777-1.888-4.166-5.66-4.166h-1.46v8.612zm18.362-12.462l7.506 18.808 7.612-18.808h4.482l-12.182 29.18-11.9-29.18h4.482zm38.332 3.85h-10.88v6.52h10.563v3.85H148.99v9.07h10.88v3.85h-14.977V11.16h14.977v3.85zm11.243 11.566l-8.86-15.416h4.71l6.207 10.845 6.222-10.845h4.71l-8.893 15.416V38.3h-4.097V26.576z" fill="#5F7278"/>
            <path d="M29.345 37.09c-1.184.447-2.9.675-5.1.675-2.008 0-3.956-.196-5.066-.33 1.508-1.87 5.575-6.656 8.48-7.757 1.055-.4 2.024-.602 2.88-.602 1.71 0 2.848.8 3.204 2.25.56 2.277-1.168 4.538-4.4 5.763M17.665 2.403l.112-.002c1.953 0 3.29 2.11 3.408 5.376.106 2.976-2.31 7.943-3.372 9.978-.982-1.986-3.17-6.724-3.277-9.7-.12-3.35 1.11-5.57 3.13-5.652m-5.967 35.36c-2.2 0-3.915-.227-5.1-.675-3.23-1.226-4.957-3.487-4.398-5.763.357-1.452 1.495-2.25 3.204-2.25.856 0 1.826.2 2.88.6 2.907 1.102 6.973 5.888 8.482 7.757-1.11.135-3.057.33-5.068.33m-3.83-14.03c-2.61-1.85-3.63-4.274-2.606-6.174.492-.913 1.302-1.396 2.342-1.396 1.02 0 2.217.47 3.465 1.354 2.33 1.654 4.843 6.823 5.788 8.9-2.012-.274-6.71-1.065-8.99-2.684m16.776-6.216c1.248-.885 2.446-1.354 3.465-1.354 1.04 0 1.85.483 2.342 1.396 1.024 1.9 0 4.324-2.606 6.174-2.282 1.62-6.98 2.41-8.99 2.685.945-2.08 3.458-7.247 5.788-8.902M34.81 31.064c-.476-1.935-2.07-3.09-4.27-3.09-.99 0-2.078.227-3.256.674-3.136 1.188-7.21 5.988-8.857 7.99v-9.05c1.646-.184 7.277-.978 10.063-2.955 3.09-2.193 4.237-5.175 2.932-7.597-.686-1.273-1.862-1.973-3.31-1.973-1.247 0-2.655.54-4.088 1.556-2.183 1.548-4.5 5.64-5.597 8.165v-5.81c.55-1.295 3.998-7.496 3.864-11.236-.17-4.754-2.537-6.44-4.508-6.44-.052 0-.107 0-.16.004-2.037.083-4.345 1.912-4.17 6.792.145 4.04 3.88 10.702 3.88 11.09v5.6c-1.098-2.524-3.437-6.618-5.62-8.167-1.434-1.018-2.856-1.557-4.103-1.557-1.447 0-2.624.7-3.31 1.973-1.306 2.42-.144 5.404 2.946 7.597 2.787 1.977 8.44 2.772 10.085 2.956v8.765c-1.645-2.14-5.65-6.572-8.647-7.707-1.178-.447-2.284-.674-3.273-.674-2.198 0-3.796 1.155-4.272 3.09-.7 2.85 1.292 5.62 5.076 7.055 1.308.495 3.163.746 5.496.746 2.365 0 4.524-.255 5.62-.39v7.783c0 .304.246.55.55.55.302 0 .547-.246.547-.55v-7.816c.55.122 3.265.422 5.83.422 2.333 0 4.173-.25 5.48-.747 3.785-1.435 5.772-4.205 5.072-7.056" stroke="#B4E7A0" fill="#B4E7A0"/>
          </g>
        </symbol>
      </svg>

      <header class="site-header">
        <div class="container">
          <div class="logo-wrapper">
            <a href="/" alt="Home"><svg class="harvey-logo"><use xlink:href="#harvey-logo"></svg></a>
          </div>
          <span class="header_phone-number">(800) 690-9989</span>
        </div>
      </header>


      <main class="signup-content">
        <div id="schedule">
          <router-view />
        </div>
      </main>

      <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
        ga('create', 'UA-89414173-1', 'auto');
        ga('send', 'pageview');
    </script>

      <!-- Scripts -->
      <script>
        window.Laravel = {!! $vue_data !!}
      </script>
      <script type="text/javascript" src="{{ mix('js/schedule/main.js') }}"></script>
    </body>
</html>
