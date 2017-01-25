@extends('_layouts.public')

@section('page_title','Log in')

@section('content')
    <login-view
        form-url="{{ secure_url('/login') }}"
        redirect-url="{{ secure_url('/dashboard') }}"
    >
        {{ csrf_field() }}
    </login-view>
@endsection