@extends('legacy._layouts.public')
@section('page_title','Reset Password')
@section('content')

<section class="page-content">
    <div class="container login-width large-top-margin">
        <div class="logo-wrapper">
            <a href="/">
                {!! $svgImages['logo'] !!}
            </a>
        </div>
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
            <footer class="card-footer">
              <div class="card-footer-item level">
                  <a href="/login" class="button login-buttons">Cancel</button></a>
                  <button type="submit" class="button is-primary login-buttons">Reset Password</button>
              </div>
            </footer>
        </form>
    </div>
</section>

@endsection
