@extends('legacy._layouts.public')
@section('page_title',$page_title)

@section('main_content')
    <section class="section bottom-padding-terms">
        <div class="container">
            <h1 class="title has-text-centered">{{$page_title}}</h1>
            <div class="content">
                {!! $html !!}
            </div>
        </div>
    </section>
@endsection
