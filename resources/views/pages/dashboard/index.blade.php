@extends('layouts.master')

@section('page_title', 'Dashboard')

<script>
  window.Laravel = {!! $vue_data !!}
  if ( Laravel.user.signedIn &&
      !Laravel.user.has_an_appointment &&
       Laravel.user.user_type === 'patient' ) {
    window.location.href = '/getting-started';
  }
</script>

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
      !function(){var analytics=window.analytics=window.analytics||[];if(!analytics.initialize)if(analytics.invoked)window.console&&console.error&&console.error("Segment snippet included twice.");else{analytics.invoked=!0;analytics.methods=["trackSubmit","trackClick","trackLink","trackForm","pageview","identify","reset","group","track","ready","alias","debug","page","once","off","on"];analytics.factory=function(t){return function(){var e=Array.prototype.slice.call(arguments);e.unshift(t);analytics.push(e);return analytics}};for(var t=0;t<analytics.methods.length;t++){var e=analytics.methods[t];analytics[e]=analytics.factory(e)}analytics.load=function(t){var e=document.createElement("script");e.type="text/javascript";e.async=!0;e.src=("https:"===document.location.protocol?"https://":"http://")+"cdn.segment.com/analytics.js/v1/"+t+"/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(e,n)};analytics.SNIPPET_VERSION="4.0.0";
      analytics.load("LY9dLmxpEcuqz1pM6nu3g3mdXo7WpIOK");
      analytics.page();
      }}();
    </script>

    <!-- Stripe -->
    <script type="text/javascript" src="https://js.stripe.com/v2"></script>

    <!-- App.js -->
    <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>

    @stack('scripts')
@stop
