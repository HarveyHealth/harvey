@extends('layouts.master')

@section('page_title', 'Dashboard')

@push('stylesheets')
    <link rel="stylesheet" href="{{ mix('css/application.css') }}">
@endpush

@section('content')
    @include('_includes.svgs')

    <div class="admin-content">
      <div id="app" :class="{ 'menu-open': global.menuOpen }">
        {{-- Alert component for handling success/error messages --}}

        <noscript>
            <div class="card noscript">
                <div class="card-section">
                    <h1 class="noscript-header">You must enable JavaScript for this site to work properly. You can do this using your browser's settings.</h1>
                </div>
            </div>
        </noscript>

        <alert></alert>

        <usernav></usernav>

        <transition
            mode="out-in"
            enter-active-class="animated animated-fast fadeIn"
            leave-active-class="animated animated-fast fadeOut"
        >
            <router-view :user="global.user"></router-view>
        </transition>

      </div>
    </div>

    <!-- Scripts -->
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

    <!-- Stripe -->
    <script type="text/javascript" src="https://js.stripe.com/v2"></script>

    <!-- App.js -->
    <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>

    @stack('scripts')
@stop
