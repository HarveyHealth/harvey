<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @if (View::hasSection('page_title'))
        <title>@yield('page_title') :: {{ config('app.name') }}</title>
    @else
        <title>{{ config('app.name') }}</title>
    @endif

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">

    {{-- CSRF TOKEN --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- STYLES --}}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"> {{-- switch to custom build icons later --}}
    @stack('stylesheets')

    {{-- TYPEKIT async load --}}
    @script(/js/libs/typekit.js)

    {{-- VENDOR SCRIPTS (mixpanel, facebook, google analytics...) --}}
    @if (App::environment('production'))
        @script({{ elixir('/js/vendors.js') }})
        <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=232862573840472&ev=PageView&noscript=1"/></noscript>
    @endif

</head>

<body class="{{ collect(\Request::segments())->implode('-') }} @yield('body_class')">
    <noscript>You must enable JavaScript for this site to work properly. You can do this using your browser's settings.</noscript>

    <div id="app">
        @include('_layouts.includes.top_nav')

        <div class="page-content">
            @yield('main_content')
        </div>

        @include('_layouts.includes.footer')
    </div>
    
    @stack('square')

    {{-- To add data here, see the VueHelperViewComposer --}}
    <script>
        window.Laravel = {!! $vue_data !!}
    </script>

    @script({{  mix('/js/manifest.js') }})
    @script({{  mix('/js/module_extract.js') }})

    @if (Auth::guest())
        @script(/js/libs/modernizr-custom.js)
        @script({{  mix('/js/app_public.js') }})
    @else
        @script(https://js.stripe.com/v2/)
        @script({{  mix('/js/app_logged_in.js') }})
    @endif

    @stack('scripts')
</body>
</html>
