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

    {{-- TYPEKIT async load --}}
    @script(/js/libs/typekit.js)

    {{-- STYLES --}}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"> {{-- switch to custom build icons later --}}
    @stylesheet(/css/app.css)
    @stack('stylesheets')

    {{-- VENDOR SCRIPTS (mixpanel, facebook, google analytics...) --}}
    @if (App::environment('production'))
        @script(/js/vendors.js)
        <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=232862573840472&ev=PageView&noscript=1"/></noscript>
    @endif

</head>

<body class="{{ collect(\Request::segments())->implode('-') }}@yield('body_class')">
    <noscript>You must enable JavaScript for this site to work properly. You can do this using your browser's settings.</noscript>

    @include('_layouts.includes.messages')

    @yield('main_content')

    {{-- To add data here, see the VueHelperViewComposer --}}
    <script>
        window.Laravel = {
        @foreach ($vue_data as $key => $value)
        "{{ $key }}" : "{{ $value }}"@if (!$loop->last),@endif

        @endforeach
        }
    </script>

    @if (Auth::guest())
        @script(/js/libs/modernizr-custom.js)
        @script(/js/app_public.js)
    @else
        <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
        @script(/js/app_logged_in.js)
    @endif

    @stack('scripts')
</body>
</html>
