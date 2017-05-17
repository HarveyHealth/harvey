@extends('layouts.master')

@section('page_title', 'This is the page title')

@push('stylesheets')
    <link rel="stylesheet" href="{{ mix('css/application.css') }}">
@endpush

@section('content')
    @include('_includes.svgs')

    <header class="site-header">
      <div class="container">
        <div class="logo-wrapper">
          <a href="/" alt="Home"><svg class="harvey-logo"><use xlink:href="#harvey-logo"></svg></a>
        </div>
        <div class="nav-right">
            <span class="nav-item">
                <a href="tel:800-690-9989" class="button is-primary is-outlined">(800) 690-9989</a>
            </span>
        </div>
      </div>
    </header>

    <main class="signup-content">
      <div id="schedule">
        <router-view />
      </div>
    </main>

    <!-- Scripts -->
    <script>
      window.Laravel = {!! $vue_data !!}
    </script>
    <script type="text/javascript" src="{{ mix('js/schedule/main.js') }}"></script>
@stop
