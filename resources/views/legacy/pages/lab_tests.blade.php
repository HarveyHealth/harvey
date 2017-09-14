@extends('legacy._layouts.public')
@section('page_title','Lab Tests')
@section('main_content')

<section class="hero">
    <div class="hero-background"></div>
    <div class="hero-body container">
        <header class="content has-text-centered">
            <h1 class="title is-3 page-title">Lab Tests</h1>
            <p class="copy-has-max-width subtitle is-5 ">Our physicians rely on specialized, evidence-based clinical laboratory tests to help validate and enhance the credibility of their proposed treatment plans.</p>
        </header>
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
                            <h3 class="title is-4"><strong>{{ $lab_test->sku->name }} Test</strong></h3>
                            <p class="subtitle is-6">Sample: {{ $lab_test->sample }}</p>
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
                <a href="/get-started" class="button is-primary is-medium has-arrow">Book Appointment</a>
            </div>
        </div>
    </div>
</section>

@endsection
