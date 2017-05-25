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

    <script src="//assets.juicer.io/embed.js" type="text/javascript"></script>
    <link href="//assets.juicer.io/embed.css" media="all" rel="stylesheet" type="text/css">

    @if (Auth::guest())
        @script(/js/vendors/modernizr-custom.js)
        <script type="text/javascript" src="{{ mix('/js/app_public.js') }}"></script>
    @else
        @script(https://js.stripe.com/v2/)
        <script type="text/javascript" src="{{ mix('/js/app.js') }}"></script>
    @endif

    @stack('scripts')
@endsection
