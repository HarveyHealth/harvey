@extends('layouts.master')

@push('stylesheets')

    <style type="text/css">{{ render_css('app_public') }}</style>
    <link rel="stylesheet" href="https://unpkg.com/gh-lity-css@1.0.0/index.css" async>
    <link rel="stylesheet" href="https://unpkg.com/gh-juicer-css@1.0.1/index.css" async>

@endpush

@section('content')

    <noscript>
        <div class="card noscript">
            <div class="card-section">
                <h1 class="noscript-header">You must enable JavaScript for this site to work properly. You can do this using your browser's settings.</h1>
            </div>
        </div>
    </noscript>

    @if (session('facebook_redirect_alert'))
    <div class="alert-bar">
        {!! session('facebook_redirect_alert') !!}
    </div>
    @endif
    <div id="app" :class="appClass">
        <grid-styles></grid-styles>
        @include('legacy._layouts.includes.top_nav')
        <div class="page-content">
            @yield('main_content')
        </div>
        <main-footer></main-footer>
    </div>

    @stack('square')

    <script type="text/javascript" src="https://unpkg.com/gh-bideo@1.0.0/index.js"></script>
    <script type="text/javascript" async>
        if (document.body.className.match('home')) {
            var detectMobile = window.matchMedia('(max-width: 768px)').matches;
            var detectIE = false;
            var ua = window.navigator.userAgent;
            var msie = ua.indexOf('MSIE ');
            if (msie > 0) {
                // IE 10 or older => return version number
                // return parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
                detectIE = true;
            }
            var trident = ua.indexOf('Trident/');
            if (trident > 0) {
                // IE 11 => return version number
                // var rv = ua.indexOf('rv:');
                // return parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10);
                detectIE = true;
            }
            var edge = ua.indexOf('Edge/');
            if (edge > 0) {
               // Edge (IE 12+) => return version number
               // return parseInt(ua.substring(edge + 5, ua.indexOf('.', edge)), 10);
               detectIE = true;
            }
            try {
                var videoLink = 'https://d35oe889gdmcln.cloudfront.net/assets/videos/hero-video.mp4';
                var bv = new Bideo();
                bv.init({
                    videoEl: document.querySelector('#hero-video'),
                    container: document.querySelector('body'),
                    resize: true,
                    autoplay: true,
                    src: [{
                        src: videoLink,
                        type: 'video/mp4'
                    }],
                    onLoad: function() {
                        // If not mobile and not IE, play video
                        if ((detectMobile === false) && (detectIE === false)) {
                            document.querySelector('#video-cover').style.display = 'none';
                        }
                    }
                });
            } catch(e){};
        }
    </script>
    <script type="text/javascript" src="https://unpkg.com/gh-zepto@1.0.0/index.js" async></script>
    <script type="text/javascript" src="https://unpkg.com/gh-juicer-js@1.0.0/index.js" async></script>
    <script type="text/javascript" src="https://unpkg.com/gh-modernizr@1.0.0/index.js"></script>
    <script type="text/javascript" src="{{ mix('js/app_public.js') }}"></script>
    <script type="text/javascript" src="https://unpkg.com/gh-lity-js@1.0.0/index.js" async></script>
    <script type="text/javascript">
        window.datacoral('trackPageView');
    </script>
    @stack('scripts')

@endsection
