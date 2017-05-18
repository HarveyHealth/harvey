@extends('layouts.master')

@section('page_title', 'Dashboard')

@push('stylesheets')
    <link rel="stylesheet" href="{{ mix('css/application.css') }}">
@endpush

@section('content')
    @include('_includes.svgs')

    <div class="admin-content">
      <div id="app">
        {{-- Alert component for handling success/error messages --}}
        <alert></alert>

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

    @script(https://js.stripe.com/v2/)
    <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>

    @stack('scripts')
@stop
