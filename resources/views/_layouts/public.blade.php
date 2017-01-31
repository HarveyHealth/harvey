@extends('_layouts.main')

@push('stylesheets')
    @stylesheet(css/app_public.css)
@endpush

@section('main_content')
    @yield('content')
@endsection