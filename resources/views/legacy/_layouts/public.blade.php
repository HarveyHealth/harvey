@extends('layouts.master')

@push('stylesheets')

    <link rel="stylesheet" href="{{ mix('css/app_public.css') }}">

@endpush

@section('content')

    <noscript>You must enable JavaScript for this site to work properly. You can do this using your browser's settings.</noscript>

    <div id="app">

        @include('legacy._layouts.includes.top_nav')

        <div class="page-content">
            @yield('main_content')
        </div>

        @include('legacy._layouts.includes.footer')

    </div>

    @stack('square')

    {{-- To add data here, see the VueHelperViewComposer --}}
    
    <script>
        window.Laravel = {!! $vue_data !!}
    </script>

    @if (Auth::guest())
        
        <!-- Modernizr -->    
        <script type="text/javascript" src="{{ mix('js/app_public.js') }}"></script>

        <!-- Juicer -->
        <link rel="stylesheet" href="https://assets.juicer.io/embed.css">
        <script type="text/javascript" src="https://assets.juicer.io/embed.js"></script>


        <!-- Lity -->
        <link rel="stylesheet" href="{{ mix('css/vendors/lity.css') }}">
        <script type="text/javascript" src="{{ mix('js/vendors/zepto.js') }}"></script>
        <script type="text/javascript" src="{{ mix('js/vendors/lity.js') }}"></script>

        <!-- Public.js -->
        <script type="text/javascript" src="{{ mix('js/app_public.js') }}"></script>

    @else

        <!-- Stripe -->
        <script type="text/javascript" src="https://js.stripe.com/v2"></script>
        
        <!-- App.js -->
        <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
    @endif

    @stack('scripts')
@endsection
