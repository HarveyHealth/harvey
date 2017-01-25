@extends('_layouts.main')

@section('main_content')
<div id="app">
    <app>
        <transition
            mode="out-in"
            enter-active-class="animated animated-fast fadeIn"
            leave-active-class="animated animated-fast fadeOut"
        >
            <router-view :user="user"></router-view>
        </transition>
    </app>
</div>
@endsection