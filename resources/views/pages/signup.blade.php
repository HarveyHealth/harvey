<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/application.css') }}">

        <!-- Typekit -->
        <script type="text/javascript" src="{{ URL::asset('js/vendors/typekit.js') }}"></script>

    </head>
    <body>

      <header class="site-header">
        <svg class="harvey-logo"><use xlink:href="#harvey-logo" /></svg>
        <span class="header_phone-number">(800) 690-9989</span>
      </header>

      <main class="signup-content">
        <div id="signup">
          <router-view />
        </div>
      </main>

      <!-- Scripts -->
      <script>
        window.Laravel = {!! $vue_data !!}
      </script>
      <script type="text/javascript" src="{{ URL::asset('js/signup/main.js') }}"></script>

    </body>
</html>
