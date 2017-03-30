@extends('_layouts.main')

@push('stylesheets')
    @if(App::environment() == "local")
        @stylesheet('css/app_public.css')
    @else
        @stylesheet({{ mix('css/app_public.css') }})
    @endif
@endpush

@section('main_content')
    @yield('content')
@endsection
