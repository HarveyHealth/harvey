@extends('_layouts.main')

@push('stylesheets')
    @if (!App::environment('local'))
        @stylesheet({{ elixir('css/app_logged_in.css') }})
    @else
        @stylesheet(/css/app_logged_in.css)
    @endif
@endpush

@section('main_content')
{{-- Alert component for handling success/error messages --}}
<alert></alert>

{{-- Transition effect is added when view changes --}}
<transition
    mode="out-in"
    enter-active-class="animated animated-fast fadeIn"
    leave-active-class="animated animated-fast fadeOut"
>
    <router-view :user="user"></router-view>
</transition>
@endsection