<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, maximum-scale=1, user-scalable=0">
        <title>0% Financing | Harvey</title>
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
        <meta name="google-site-verification" content="6UgpVyc1D8nu-29CNMYM4WacQe4_lepLVOAuuDqbadc" />
        <meta property="fb:app_id" content="383090978468158">
        <link type="image/x-icon" rel="apple-touch-icon-precomposed" href="https://d35oe889gdmcln.cloudfront.net/assets/images/icon.png">
        <link type="image/x-icon" rel="shortcut icon" href="https://d35oe889gdmcln.cloudfront.net/assets/images/favicon.ico">
        <link type="image/x-icon" rel="icon" href="https://d35oe889gdmcln.cloudfront.net/assets/images/icon.png">
        <!-- Typography -->
        <script src="https://use.typekit.net/ukw4upn.js"></script>
        <script>try{Typekit.load({ async: true });}catch(e){}</script>
        <link rel="stylesheet" href="https://unpkg.com/gh-font-awesome@1.0.4/index.css">
        <link rel="stylesheet" href="{{ mix('css/application.css') }}">
        @stack('stylesheets')
        <script>
          window.Laravel = {!! $vue_data !!};
          window.$$context = 'financing';
        </script>
    </head>
    <body>

      @include('_includes.svgs')

      <main>
        <div id="app">
          <router-view />
        </div>
      </main>

      <footer>
        <script type="text/javascript" src="https://js.stripe.com/v2"></script>
        <script type="text/javascript" src="https://js.stripe.com/v3"></script>
        <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
      </footer>
    </body>
</html>
