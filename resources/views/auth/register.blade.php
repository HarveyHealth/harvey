@extends('_layouts.public', ['view' => 'register-view'])

@section('page_title', 'Sign Up')
@section('body_class', ' hero is-fullheight is-primary is-bold')

@section('content')
    <register-view
        form-url="{{ url('/register') }}"
        :old="{{ json_encode(Session::getOldInput()) }}"
    >
        {{ csrf_field() }}
    </register-view>
@endsection