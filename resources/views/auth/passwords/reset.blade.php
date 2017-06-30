@extends('legacy._layouts.public')

@section('page_title','Reset Password')

@section('content')
<section class="signup-content">
    <div class="container small login-width">
        <img src="/images/signup/tree.png" class="registration-tree">
        <h1 class="header-xlarge login-heading">Reset Password</h1>
        <div class="card card-padding">
            <div class="card-section">
                <form role="form" method="POST" action="/password/reset">
                    {{ csrf_field() }}

                    <input type="hidden" name="token" value="{{ $token }}">

                    <label class="label">Email</label>
                    <p class="control has-icon{{ $errors->has('email') ? ' has-icon has-icon-right' : '' }}">
                        <input class="input{{ $errors->has('email') ? ' is-danger' : '' }}" type="email" placeholder="Email" name="email" value="{{ $email or old('email') }}" required{{ !isset($errors) || count($errors) == 0 || $errors->has('email') ? ' autofocus' : '' }}>
                        <span class="icon is-small"><i class="fa fa-envelope"></i></span>
                        @if ($errors->has('email'))
                            <span class="icon is-small"><i class="fa fa-warning"></i></span>
                            <span class="help is-danger">{{ $errors->first('email') }}</span>
                        @endif
                    </p>

                    <label class="label">Password</label>
                    <p class="control has-icon{{ $errors->has('password') ? ' has-icon has-icon-right' : '' }}">
                        <input class="input{{ $errors->has('password') ? ' is-danger' : '' }}" type="password" placeholder="Password" name="password" required{{ $errors->has('password') ? ' autofocus' : '' }}>
                        <span class="icon is-small"><i class="fa fa-lock"></i></span>
                        @if ($errors->has('password'))
                            <span class="icon is-small"><i class="fa fa-warning"></i></span>
                            <span class="help is-danger">{{ $errors->first('password') }}</span>
                        @endif
                    </p>

                    <label class="label">Confirm Password</label>
                    <p class="control has-icon">
                        <input class="input" type="password" placeholder="Confirm Password" name="password_confirmation" required>
                        <span class="icon is-small"><i class="fa fa-lock"></i></span>
                    </p>

                    <p class="control is-clearfix">
                        <button type="submit" class="button is-primary is-pulled-right">Reset Password</button>
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
