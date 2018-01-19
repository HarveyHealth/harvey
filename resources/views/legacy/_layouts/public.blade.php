@extends('layouts.master')

@push('stylesheets')

    <style><?php include("css/app_public.css"); ?></style>

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
        @include('legacy._layouts.includes.top_nav')
        <div class="page-content">
            @yield('main_content')
        </div>
        @include('legacy._layouts.includes.footer')
    </div>

    @stack('square')

    {{-- To add data here, see the VueHelperViewComposer --}}

    <script>
        window.Laravel = {!! $vue_data !!};
    </script>

    <script type="text/javascript" src="https://unpkg.com/gh-bideo@1.0.0/index.js"></script>
    <script type="text/javascript" async>
        if (document.body.className.match('home')) {var detectMobile = window.matchMedia('(max-width: 768px)').matches;var detectIE = false;var ua = window.navigator.userAgent;var msie = ua.indexOf('MSIE ');if (msie > 0) {detectIE = true;}var trident = ua.indexOf('Trident/');if (trident > 0) {detectIE = true;}var edge = ua.indexOf('Edge/');if (edge > 0) {detectIE = true;}try {var videoLink = 'https://d35oe889gdmcln.cloudfront.net/assets/videos/hero-video.mp4';var bv = new Bideo();bv.init({videoEl: document.querySelector('#hero-video'),container: document.querySelector('body'),resize: true,autoplay: true,src: [{src: videoLink,type: 'video/mp4'}],onLoad: function() {if ((detectMobile === false) && (detectIE === false)) {document.querySelector('#video-cover').style.display = 'none';}}});} catch(e){};}
    </script>
    <script type="text/javascript" src="https://unpkg.com/gh-zepto@1.0.0/index.js" async></script>
    <script type="text/javascript" src="https://unpkg.com/gh-juicer-js@1.0.0/index.js" async></script>
    <script type="text/javascript" src="https://unpkg.com/gh-modernizr@1.0.0/index.js"></script>
    <script type="text/javascript" src="{{ mix('js/app_public.js') }}"></script>
    <script type="text/javascript" src="https://unpkg.com/gh-lity-js@1.0.0/index.js" async></script>
    <script type="text/javascript">
        @isset($conditions)
            App.Public.setConditions({!! $conditions !!});
        @endisset
    </script>
    @stack('scripts')

@endsection
