@extends('_layouts.public')
@section('page_title','About ' . config('app.name'))

@push('stylesheets')
    {{-- use @stylesheet(path/to/style.css) here --}}
@endpush

@push('scripts')
    {{-- use @script(path/to/script.js) here --}}
@endpush




@section('content')

<h1>About {{ config('app.name')}}</h1>

@endsection
