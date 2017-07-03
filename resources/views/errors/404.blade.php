@extends('legacy._layouts.public')
@section('page_title','404')

@section('main_content')
    <section class="section grey_bg">
        <div class="container has-text-centered">
            <div class="card noscript">
                <div class="card-section">
                    <h1 class="noscript-header">Whoops! Seems you're lost!</h1>
                    <a href="/"><button class="button is-pulled-right login-buttons fourButton">Take me home</button></a>
                </div>
            </div>
        </div>
    </section>
@endsection
