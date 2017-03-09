@extends('_layouts.public')
@section('page_title',$page_title)

@push('stylesheets')
    @stylesheet({{ mix('css/legal.css')}})
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
