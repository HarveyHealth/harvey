@extends('legacy._layouts.public')
@section('page_title',$page_title)

@push('stylesheets')
    <link rel="stylesheet" href="{{ mix('legacy/css/legal.css') }}">
@endpush

@section('content')
    <section class="section">
        <div class="container">
            <h1 class="title has-text-centered">{{$page_title}}</h1>
            <div class="content">
                {!! $html !!}
            </div>
        </div>
    </section>
@endsection
