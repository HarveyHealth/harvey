@extends('legacy._layouts.public')

@section('page_title','Reset Password')

@section('content')
<section class="signup-content reset-top">
    <div class="container small login-width">
        <img src="/images/signup/tree.png" class="registration-tree">
        <h1 class="header-xlarge login-heading">Reset Password</h1>
        <form role="form" method="POST" action="/password/reset">
            <div class="card card-padding">
                <div class="card-section">
                    {{ csrf_field() }}

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="input-wrap">
                        <label :class="{typed: reset.form.email}" class="hoverInput">Email</label>
                        <input class="login-input input{{ $errors->has('email') ? ' is-danger' : '' }}" type="email" placeholder="Email" name="email" value="{{ $email or old('email') }}" required{{ !isset($errors) || count($errors) == 0 || $errors->has('email') ? ' autofocus' : '' }}>
                        @if ($errors->has('email'))
                            <span class="help is-danger">{{ $errors->first('email') }}</span>
                        @endif
                    </div>

                    <div class="input-wrap">
                        <label :class="{typed: reset.form.password}" class="hoverInput">Password</label>
                        <input class="login-input input{{ $errors->has('password') ? ' is-danger' : '' }}" type="password" placeholder="Password" name="password" required{{ $errors->has('password') ? ' autofocus' : '' }}>
                    </div>

                    <div class="input-wrap">
                        <label :class="{typed: reset.form.password_confirmation}" class="hoverInput">Confirm Password</label>
                        <input class="login-input input{{ $errors->has('password') ? ' is-danger' : '' }}" type="password" placeholder="Confirm Password" name="password_confirmation"  required{{ $errors->has('password') ? ' autofocus' : '' }}>
                        @if ($errors->has('password'))
                            <span class="help is-danger">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <button type="submit" class="button is-primary login-buttons login-top-margin reset-width">Reset Password</button>
        </form>
    </div>
</section>
@endsection
