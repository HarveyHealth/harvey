<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Intake</title>

    <script type="text/javascript">
      window.Laravel = {!! $vue_data !!}
      window.$$context = 'intake';
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
