@extends('_layouts.public', ['view' => 'register-view'])

@section('page_title', 'Sign Up')

@section('content')
    <register-view
        form-url="{{ secure_url('/register') }}"
        redirect-url="/dashboard#/new-appointment"
    >
        {{ csrf_field() }}
    </register-view>
@endsection