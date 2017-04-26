@extends('legacy._layouts.main')

@push('stylesheets')
    <link rel="stylesheet" href="{{ mix('legacy/css/app_public.css') }}">
@endpush

@section('main_content')
    @yield('content')
@endsection
