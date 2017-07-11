<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Intake</title>

    <script type="text/javascript">
      window.Laravel = {!! $vue_data !!}
      window.$$context = 'intake';
      /*
      if (!Laravel.user.signedIn) {
        window.location.hash = '/signup';
      } else if (Laravel.user.has_an_appointment || Laravel.user.user_type !== 'patient') {
        window.location.href = '/dashboard';
      } else {
        window.location.hash = '/welcome';
      }
      */
    </script>

    <!-- Typekit -->
    <script type="text/javascript" src="{{ mix('js/vendors/typekit.js') }}"></script>

  </head>
  <body>
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
