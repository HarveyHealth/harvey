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