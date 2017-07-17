@extends('layouts.master')

@section('page_title', 'Dashboard')

<script>
  window.Laravel = {!! $vue_data !!}
  if ( Laravel.user.signedIn &&
      !Laravel.user.has_an_appointment &&
       Laravel.user.user_type === 'patient' ) {
    window.location.href = '/get-started';
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

    <!-- Stripe -->
    <script type="text/javascript" src="https://js.stripe.com/v2"></script>

    <!-- App.js -->
    <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>

    @stack('scripts')
@stop
