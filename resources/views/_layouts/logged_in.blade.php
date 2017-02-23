@extends('_layouts.main')

@push('stylesheets')
    @stylesheet({{ mix('css/app_logged_in.css') }})
@endpush

@section('main_content')
<alert></alert>
<transition
    mode="out-in"
    enter-active-class="animated animated-fast fadeIn"
    leave-active-class="animated animated-fast fadeOut"
>
    <router-view :user="user"></router-view>
</transition>
@endsection