@extends('_layouts.public', ['view' => 'login-view'])

@section('page_title','Log in')
@section('body_class', ' hero is-fullheight is-primary is-bold')

@section('content')
    <login-view
        form-url="{{ url('/login') }}"
        :old="{{ json_encode(Session::getOldInput()) }}"
    >
        <slot name="csrf_field">
            {{ csrf_field() }}
        </slot>
    </login-view>
@endsection