@extends('_layouts.public')

@section('page_title','Log in')
@section('body_class', ' hero is-fullheight is-primary is-bold')

@section('content')
    <login-view
        form-url="{{ url('/login') }}"
        :old="{{ json_encode(Session::getOldInput()) }}"
    >
        {{ csrf_field() }}
    </login-view>
@endsection