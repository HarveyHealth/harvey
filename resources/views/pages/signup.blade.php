<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Signup | Harvey</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/application.css') }}">

        <!-- Typekit -->
        <script type="text/javascript" src="{{ mix('js/vendors/typekit.js') }}"></script>

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

      <div class="header nav" @if (Auth::guest()) :class="{'is-inverted': navIsInverted}" @endif >
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
                      <a href="/login" class="button is-primary is-outlined is-hidden-mobile">Log In</a>
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
        localStorage.setItem('signing up', 'true')
      </script>

      <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
        ga('create', 'UA-89414173-1', 'auto');
        ga('send', 'pageview');
      </script>
    
      <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>

    </body>
</html>