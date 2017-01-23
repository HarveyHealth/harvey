@extends('_layouts.public')

@section('page_title','Log in')

@section('content')
    <login-view
        form-url="{{ url('/login') }}"
        redirect-url="{{ url('/dashboard') }}"
    >
        {{ csrf_field() }}
    </login-view>
@endsection