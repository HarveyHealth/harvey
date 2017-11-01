<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, maximum-scale=1, user-scalable=0">
        @if (View::hasSection('page_title'))
            <title>@yield('page_title') | Harvey</title>
        @else
            <title>Personalized, Holistic & Integrative Medicine | Harvey</title>
        @endif
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="Harvey">
        <meta property="og:locale" content="en_US">
        <meta property="og:url" content="https://www.goharvey.com">
        <meta property="og:image" content="https://d35oe889gdmcln.cloudfront.net/assets/images/social-share.jpg">
        <meta property="og:description" name="description" content="Harvey is the leading telehealth provider of personalized and integrative medicine. We empower people to find natural and holistic remedies to chronic health conditions — without leaving their homes.">
        <meta name="keywords" content="holistic health, holistic medicine, vitamins and supplements, naturopathic, integrative medicine, supplements, naturopathic doctor, chiropractor, vitamins, functional medicine, homeopathic medicine, homeopathy, naturopathic medicine, alternative medicine, natural health, acupuncture, eastern medicine, telehealth, naturopathy, natural cure, genomics, lab tests, integrative doctor, functional doctor">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="msapplication-config" content="none"/>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="robots" content="">
        <link type="application/rss+xml" rel="alternate" title="RSS" href="https://blog.goharvey.com/feed">
        <meta name="google-site-verification" content="X_qk9hRyP9xKTYUV7T2K7ou4_ONozH_Z0d0uRN-CBz0" />
        <meta property="fb:app_id" content="383090978468158">
        <link type="image/x-icon" rel="apple-touch-icon-precomposed" href="https://d35oe889gdmcln.cloudfront.net/assets/images/icon.png">
        <link type="image/x-icon" rel="shortcut icon" href="https://d35oe889gdmcln.cloudfront.net/assets/images/favicon.ico">
        <link type="image/x-icon" rel="icon" href="https://d35oe889gdmcln.cloudfront.net/assets/images/icon.png">
        <!-- Typography -->
        <script src="https://use.typekit.net/ukw4upn.js"></script>
        <script>try{Typekit.load({ async: true });}catch(e){}</script>
        <link rel="stylesheet" href="https://unpkg.com/gh-font-awesome@1.0.4/index.css">
        @stack('stylesheets')
    </head>

    <body class="{{ collect(\Request::segments())->implode('-') }} @yield('body_class')">
      @yield('content')
        <footer>
            <!-- Scripts -->
            @if (isNotProd())
                <script type="text/javascript" src="https://unpkg.com/gh-intercom@1.0.0/index.js"></script>
                <script type="text/javascript">
                    window.onload = function() {
                        window.intercomSettings = {
                            app_id: 'tgn5rh80',
                            user_id: {{ currentUser() ? 'Laravel.user.id' : 'null' }},
                            user_hash: {{ currentUser() ? 'Laravel.user.intercom_hash' : 'null' }}
                        };
                    }
                </script>
            @endif
            <script>
                !function(){var analytics=window.analytics=window.analytics||[];if(!analytics.initialize)if(analytics.invoked)window.console&&console.error&&console.error("Segment snippet included twice.");else{analytics.invoked=!0;analytics.methods=["trackSubmit","trackClick","trackLink","trackForm","pageview","identify","reset","group","track","ready","alias","debug","page","once","off","on"];analytics.factory=function(t){return function(){var e=Array.prototype.slice.call(arguments);e.unshift(t);analytics.push(e);return analytics}};for(var t=0;t<analytics.methods.length;t++){var e=analytics.methods[t];analytics[e]=analytics.factory(e)}analytics.load=function(t){var e=document.createElement("script");e.type="text/javascript";e.async=!0;e.src=("https:"===document.location.protocol?"https://":"http://")+"cdn.segment.com/analytics.js/v1/"+t+"/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(e,n)};analytics.SNIPPET_VERSION="4.0.0";
                analytics.load(Laravel.services.segment.key);
                analytics.page();
                }}();
            </script>
        </footer>
    </body>
</html>
