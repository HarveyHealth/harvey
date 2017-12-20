@extends('layouts.master')

@section('page_title', 'Dashboard')

@push('stylesheets')
    <link rel="stylesheet" href="{{ mix('css/application.css') }}">
@endpush

@section('content')
    @include('_includes.svgs')

    <script type="text/javascript">
      window.Laravel = {!! $vue_data !!}
      window.$$context = 'intake';
    </script>

    <main>
        <div id="app">
            <router-view />
        </div>
    </main>

    <!-- Stripe -->
    <script type="text/javascript" src="https://js.stripe.com/v2"></script>

    <!-- App.js -->
    <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>

    @stack('scripts')
@stop
