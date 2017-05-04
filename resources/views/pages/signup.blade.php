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
        <span class="header_phone-number">(800) 690-9989</span>
      </div>
    </header>

    <main class="signup-content">
      <div id="signup">
        <router-view />
      </div>
    </main>

    <!-- Scripts -->
    <script>
      window.Laravel = {!! $vue_data !!}
    </script>
    <script type="text/javascript" src="{{ mix('js/signup/main.js') }}"></script>
@stop
