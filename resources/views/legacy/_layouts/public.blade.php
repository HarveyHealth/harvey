@extends('layouts.master')

@push('stylesheets')

    @stylesheet(css/app_public.css)

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
        @script(/js/vendors/modernizr-custom.js)

        <!-- Juicer -->
        @script(https://assets.juicer.io/embed.js)
        @stylesheet(https://assets.juicer.io/embed.css)

        <!-- Lity -->
        @stylesheet(css/vendors/lity.css)
        @script(js/vendors/zepto.js)
        @script(js/vendors/lity.js)

        <!-- Public.js -->
        <script type="text/javascript" src="{{ mix('/legacy/js/app_public.js') }}"></script>

    @else

        <!-- Stripe -->
        @script(js.stripe.com/v2/)
        
        <!-- App.js -->
        <script type="text/javascript" src="{{ mix('/legacy/js/app_logged_in.jss') }}"></script>

    @endif

    @stack('scripts')
@endsection
