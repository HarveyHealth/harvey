@extends('legacy._layouts.public')
@section('page_title','404 Error')
@section('body_class','page-404')
@section('main_content')

<section class="page-content">
    <div class="container login-width large-top-margin">
        <div class="logo-wrapper">
            <a href="/">
                {!! $svgImages['logo'] !!}
            </a>
        </div>
        <div class="card card-padding">
            <div class="card-section">
                <h1>Whoops!</h1>
                <h2>Sorry, the page you were looking for could not be found.</h2>
                <p>You can return to our <a href="/">homepage</a> or start a chat with us at the bottom-right corner of any page.</p>
            </div>
        </div>
    </div>
</section>

@endsection
