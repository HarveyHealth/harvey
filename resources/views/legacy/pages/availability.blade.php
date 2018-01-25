@extends('legacy._layouts.public')
@section('page_title','Availability')
@section('body_class','page-availability')
@section('main_content')

<section class="ph3 pv6">
    <div class="mha mw7">
        <availability :product-availability="Blade.availability"></availability>
    </div>
</section>

@endsection
