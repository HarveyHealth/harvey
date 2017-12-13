<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Intake | Harvey</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ mix('css/application.css') }}">

    <script type="text/javascript">
      window.Laravel = {!! $vue_data !!}
      window.$$context = 'intake';

      // if (!Laravel.user.signed_in) {
      //   window.location.hash = '/signup';
      // } else if (Laravel.user.has_an_appointment || Laravel.user.user_type !== 'patient') {
      //   window.location.href = '/dashboard';
      // } else {
      //   window.location.hash = '/welcome';
      // }

    </script>

    <!-- Typekit -->
    <script type="text/javascript" src="{{ mix('js/vendors/typekit.js') }}"></script>

  </head>
  <body>

    @include('_includes.svgs')

    <main>
      <div id="app">
        <router-view />
      </div>
    </main>

    <!-- Stripe -->
    <script type="text/javascript" src="https://js.stripe.com/v2"></script>

    <!-- App.js -->
    <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>

  </body>
</html>
