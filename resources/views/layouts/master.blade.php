<!DOCTYPE html>
<html lang="en">
    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">

        @if (View::hasSection('page_title'))
            <title>Harvey | @yield('page_title')</title>
        @else
            <title>Harvey | Personalized integrative medicine</title>
        @endif

        <!-- OG -->
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="Harvey">
        <meta property="og:url" content="https://www.goharvey.com">
        <meta property="og:image" content="https://www.goharvey.org/img/social-share.jpg">

        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="msapplication-config" content="none"/>
        <meta name="robots" content="">

        <!-- CSRF TOKEN -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- icons -->
        <link type="image/x-icon" rel="apple-touch-icon-precomposed" href="/img/icon.jpg">
        <link type="image/x-icon" rel="shortcut icon" href="/img/favicon.ico">
        <link type="image/x-icon" rel="icon" href="/img/favicon.ico">

        <!-- RSS -->
        <link type="application/rss+xml" rel="alternate" title="RSS" href="https://blog.goharvey.com/feed">

        <meta name="description" content="Harvey is a telehealth provider of personalized and integrative medicine. Consult with functional and naturopathic doctors to find the root cause of chronic health conditions.">
        <meta name="keywords" content="holistic health, holistic medicine, vitamins and supplements, naturopathic, integrative medicine, supplements, naturopathic doctor, chiropractor, vitamins, functional medicine, homeopathic medicine, homeopathy, naturopathic medicine, alternative medicine, natural health, acupuncture, eastern medicine, telehealth, naturopathy, natural cure, genomics, lab tests, integrative doctor, functional doctor">

        <!-- <meta name="google-site-verification" content="X_qk9hRyP9xKTYUV7T2K7ou4_ONozH_Z0d0uRN-CBz0" /> -->
        <!-- <meta property="fb:app_id" content="383090978468158"> -->

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="//cdn-images.mailchimp.com/embedcode/horizontal-slim-10_7.css">
        @stack('stylesheets')

        <!-- Typekit -->
        <script type="text/javascript" src="{{ mix('js/vendors/typekit.js') }}"></script>

        @if (App::environment(['production', 'staging']))
            <script type="text/javascript" src="{{ mix('js/vendors/intercom.js') }}"></script>
        @endif

        <script>
            !(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
            ga('create', 'UA-89414173-1', 'auto');
            ga('send', 'pageview');
        </script>
    </head>
    <body class="{{ collect(\Request::segments())->implode('-') }} @yield('body_class')">
        @yield('content')
    </body>
</html>
