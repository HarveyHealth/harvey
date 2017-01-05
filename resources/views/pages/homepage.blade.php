@extends('_layouts.public')
@section('page_title','Welcome to Harvey')

@push('stylesheets')
@endpush

@push('scripts')
    @script(/js/example.js)
@endpush




@section('content')

<div class="container">
    <h2>Welcome to Harvey</h2>
</div>

@endsection
