@extends('_layouts.public')

@section('page_title','Log in')
@section('body_class', ' hero is-fullheight is-primary is-bold')

@section('content')
    <login-view
        form-url="{{ url('/login') }}"
        redirect-url="{{ url('/dashboard') }}"
    >
        {{ csrf_field() }}
    </login-view>
@endsection