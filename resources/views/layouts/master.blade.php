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

        <!-- SEO -->
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="Harvey">
        <meta property="og:url" content="https://www.goharvey.com">
        <meta property="og:image" content="https://www.goharvey.org/img/social-share.jpg">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="msapplication-config" content="none"/>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="robots" content="">
        <meta name="description" content="Harvey is a telehealth provider of personalized and integrative medicine. Consult with functional and naturopathic doctors to find the root cause of chronic health conditions.">
        <meta name="keywords" content="holistic health, holistic medicine, vitamins and supplements, naturopathic, integrative medicine, supplements, naturopathic doctor, chiropractor, vitamins, functional medicine, homeopathic medicine, homeopathy, naturopathic medicine, alternative medicine, natural health, acupuncture, eastern medicine, telehealth, naturopathy, natural cure, genomics, lab tests, integrative doctor, functional doctor">

        <!-- Icons -->
        <link type="image/x-icon" rel="apple-touch-icon-precomposed" href="/img/icon.jpg">
        <link type="image/x-icon" rel="shortcut icon" href="/img/favicon.ico">
        <link type="image/x-icon" rel="icon" href="/img/favicon.ico">

        <!-- RSS -->
        <link type="application/rss+xml" rel="alternate" title="RSS" href="https://blog.goharvey.com/feed">

        <meta name="description" content="Harvey is a telehealth provider of personalized and integrative medicine. Consult with functional and naturopathic doctors to find the root cause of chronic health conditions.">
        <meta name="keywords" content="holistic health, holistic medicine, vitamins and supplements, naturopathic, integrative medicine, supplements, naturopathic doctor, chiropractor, vitamins, functional medicine, homeopathic medicine, homeopathy, naturopathic medicine, alternative medicine, natural health, acupuncture, eastern medicine, telehealth, naturopathy, natural cure, genomics, lab tests, integrative doctor, functional doctor">

        <meta name="google-site-verification" content="X_qk9hRyP9xKTYUV7T2K7ou4_ONozH_Z0d0uRN-CBz0" />
        <meta property="fb:app_id" content="383090978468158">

        <!-- Typekit -->
        <script type="text/javascript" src="{{ mix('js/vendors/typekit.js') }}"></script>

        <!-- Lity -->
        <link href="{{ mix('css/vendors/lity.css') }}" rel="stylesheet">
        <script src="{{ mix('js/vendors/zepto.js') }}"></script>
        <script src="{{ mix('js/vendors/lity.js') }}"></script>

        <!-- Styles -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="//cdn-images.mailchimp.com/embedcode/horizontal-slim-10_7.css">
        @stack('stylesheets')

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
            fbq('track', 'PageView');
            </script>
            <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=170447220119877&ev=PageView&noscript=1"
            /></noscript>
            <!-- DO NOT MODIFY -->
            <!-- End Facebook Pixel Code -->

    </head>
    <body class="{{ collect(\Request::segments())->implode('-') }} @yield('body_class')">
        @yield('content')
    </body>
</html>