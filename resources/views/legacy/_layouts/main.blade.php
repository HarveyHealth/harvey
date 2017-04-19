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
    <script type="text/javascript" src="{{ URL::asset('/js/vendors/typekit.js') }}"></script>
    
    @if (App::environment('production'))
        @script(js/vendors/intercom.js)
    @endif

</head>

<body class="{{ collect(\Request::segments())->implode('-') }} @yield('body_class')">

    <noscript>You must enable JavaScript for this site to work properly. You can do this using your browser's settings.</noscript>

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
        @script(/js/vendors/modernizr-custom.js)
        @if(App::environment() == "local")
            <script type="text/javascript" src="{{ URL::asset('/legacy/js/app_public.js') }}"></script>
        @else
            <script type="text/javascript" src="{{ URL::asset('/legacy/js/app_public.js') }}"></script>
        @endif

    @else
        @script(https://js.stripe.com/v2/)
        @if(App::environment() == "local")
            <script type="text/javascript" src="{{ URL::asset('/legacy/js/app_logged_in.js') }}"></script>
        @else
            <script type="text/javascript" src="{{ URL::asset('/legacy/js/app_logged_in.jss') }}"></script>
        @endif

    @endif

    @stack('scripts')

</body>
</html>
