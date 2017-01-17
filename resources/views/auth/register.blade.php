@extends('_layouts.public', ['view' => 'register-view'])

@section('page_title', 'Sign Up')
@section('body_class', ' hero is-fullheight is-primary is-bold')

@section('content')
    <register-view
        form-url="{{ url('/register') }}"
        :old="{{ json_encode(Session::getOldInput()) }}"
    >
        <slot name="csrf_field">
            {{ csrf_field() }}
        </slot>
    </register-view>
@endsection