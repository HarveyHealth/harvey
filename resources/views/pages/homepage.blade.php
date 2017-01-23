@extends('_layouts.public')
@section('page_title','Welcome to Harvey')
@section('body_class','home')

@push('stylesheets')
@endpush

@push('scripts')
    {{-- @script(/js/example.js) --}}
@endpush


@section('content')
    <home-view></home-view>
@endsection
