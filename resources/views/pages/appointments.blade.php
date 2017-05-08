@extends('layouts.master')

@section('page_title', 'Appointments')

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
            <router-view :user="user"></router-view>
        </transition>

      </div>
    </div>

    <!-- Scripts -->
    @stack('square')

    {{-- To add data here, see the VueHelperViewComposer --}}
    <script>
      window.Laravel = {!! $vue_data !!}
    </script>

    @script(https://js.stripe.com/v2/)
    <script type="text/javascript" src="{{ mix('js/appointments/main.js') }}"></script>

    @stack('scripts')
@stop
