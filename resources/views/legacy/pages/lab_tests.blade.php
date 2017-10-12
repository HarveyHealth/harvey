@extends('legacy._layouts.public')
@section('page_title','Lab Tests')
@section('main_content')

<section class="hero hero-background">
    <div id="hero-video-container">
        <video id="hero-video" autoplay loop muted></video>
        <div id="video-cover"></div>
        <div id="overlay"></div>
    </div>
    <div class="hero-body">
        <div class="container">
            <div class="columns">
                <div class="column is-7 is-6-desktop">
                    <h1 class="title is-1">Home Lab Testing</h1>
                    <p class="subtitle is-5">Our integrative doctors rely on a wide range of specialized, in-home lab tests to help validate and enhance the credibility of their diagnosis and treatment plans.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section is-narrow">
    <div class="container bg-white">
        <vertical-tabs load-with-id="{{ $sku_id }}">
            @foreach ($lab_tests as $lab_test)
                <vertical-tab class="tab" label="{{ $lab_test->sku->name }}" url="{{ $lab_test->sku->slug }}">
                    <header class="level">
                        <div class="media-left is-pulled-left">
                            <img src="{{ $lab_test->image }}" alt="">
                        </div>
                        <div class="media-content">
                            <h3 class="title font-xl"><strong>{{ $lab_test->sku->name }} Test</strong></h3>
                            @if ($lab_test->example)
                                <a class="link font-lg" href="{{ $lab_test->example }}" target="_blank">What does this test measure?</a>
                            @endif
                            <p class="font-md">Sample: {{ $lab_test->sample }}</p>
                        </div>
                        <div class="media-right">
                            <p class="title is-3">${{ number_format($lab_test->sku->price) }}</p>
                        </div>
                    </header>
                    <div class="content">
                        <blockquote>"{!! $lab_test->quote !!}"</blockquote>
                        {!! $lab_test->description !!}
                    </div>
                </vertical-tab>
            @endforeach
        </vertical-tabs>
    </div>
</section>

<section class="section" id="get-started">
    <div class="container">
        <div class="has-text-centered">
            <h2 class="title is-3 is-padding-bottom">Start your journey to better health.</h2>
            <div class="button-wrapper">
                <a href="/conditions" class="button is-primary is-medium has-arrow">Get Started</a>
            </div>
        </div>
    </div>
</section>

@endsection
