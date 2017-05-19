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

    <!-- Facebook Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
    n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
    document,'script','https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '170447220119877'); // Insert your pixel ID here.
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=170447220119877&ev=PageView&noscript=1"
    /></noscript>
    <!-- DO NOT MODIFY -->
    <!-- End Facebook Pixel Code -->
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

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
        ga('create', 'UA-89414173-1', 'auto');
        ga('send', 'pageview');
    </script>

    @if (Auth::guest())
        @script(/js/vendors/modernizr-custom.js)
        <script type="text/javascript" src="{{ mix('/legacy/js/app_public.js') }}"></script>
    @else
        @script(https://js.stripe.com/v2/)
        <script type="text/javascript" src="{{ mix('/legacy/js/app_logged_in.jss') }}"></script>
    @endif

    @stack('scripts')

</body>
</html>
