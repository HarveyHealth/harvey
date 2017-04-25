@extends('legacy._layouts.main')

@push('stylesheets')
    @if(App::environment() == "local")
        <link rel="stylesheet" href="{{ URL::asset('legacy/css/app_public.css') }}">
    @else
        <link rel="stylesheet" href="{{ mix('legacy/css/app_public.css') }}">
    @endif
@endpush

@section('main_content')
    @yield('content')
@endsection
