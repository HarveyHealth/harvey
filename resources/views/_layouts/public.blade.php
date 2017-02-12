@extends('_layouts.main')

@push('stylesheets')
    @if (!App::environment('local'))
        @stylesheet({{ elixir('css/app_public.css') }})
    @else
        @stylesheet(/css/app_public.css)
    @endif
@endpush

@section('main_content')
    @yield('content')
@endsection
