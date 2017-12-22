@extends('layouts.master')

@section('page_title', 'Patient Intake')

    <style><?php include("css/application.css");?></style>

@section('content')
    @include('_includes.svgs')

    <script type="text/javascript">
      window.Laravel = {!! $vue_data !!};
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
