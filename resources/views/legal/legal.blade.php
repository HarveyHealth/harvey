@extends('_layouts.public')
@section('page_title',$page_title)

@push('stylesheets')
    @stylesheet(/css/legal.css)
@endpush

@push('scripts')
    {{-- use @script(path/to/script.js) here --}}
@endpush




@section('content')
    <section class="section">
        <div class="container">
            <h1 class="title has-text-centered">{{$page_title}}</h1>
            {!! $html !!}
        </div>
    </section>
@endsection
