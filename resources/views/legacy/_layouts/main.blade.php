<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @if (View::hasSection('page_title'))
        <title>@yield('page_title') | Harvey</title>
    @else
        <title>Harvey | Personalized integrative medicine</title>
    @endif

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">

    {{-- CSRF TOKEN --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- STYLES --}}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"> {{-- switch to custom build icons later --}}
    <link rel="stylesheet" href="//cdn-images.mailchimp.com/embedcode/horizontal-slim-10_7.css">
    @stack('stylesheets')

    {{-- TYPEKIT async load --}}
    <script type="text/javascript" src="{{ mix('/js/vendors/typekit.js') }}"></script>

    @if (App::environment(['production', 'staging']))
        <script type="text/javascript" src="{{ mix('js/vendors/intercom.js') }}"></script>
    @endif
</head>

<body class="{{ collect(\Request::segments())->implode('-') }} @yield('body_class')">

    <noscript>
        <div class="card noscript">
            <div class="card-section">
                <h1 class="noscript-header">You must enable JavaScript for this site to work properly. You can do this using your browser's settings.</h1>
            </div>
        </div>
    </noscript>


    <div id="app">
        @include('legacy._layouts.includes.top_nav')

        <div class="page-content">
            @yield('main_content')
        </div>

        @include('legacy._layouts.includes.footer')
    </div>

    @stack('square')

    {{-- To add data here, see the VueHelperViewComposer --}}
    <script>
        window.Laravel = {!! $vue_data !!}
    </script>

    @if (Auth::guest())

        <!-- Modernizr -->
        <script type="text/javascript" src="{{ mix('js/vendors/modernizr-custom.js') }}"></script>

        <!-- Public.js -->
        <script type="text/javascript" src="{{ mix('legacy/js/app_public.js') }}"></script>

    @else

        <!-- Stripe -->
        <script type="text/javascript" src="https://js.stripe.com/v2/"></script>

        <!-- Logged_in.js -->
        <script type="text/javascript" src="{{ mix('legacy/js/app_logged_in.jss') }}"></script>

    @endif

    @stack('scripts')

</body>
</html>
