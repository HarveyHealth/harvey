@extends('_layouts.public')

@section('page_title','Log in')
@section('body_class', ' hero is-fullheight is-primary is-bold')

@section('content')
<section class="section">
    <div class="container">
        <header class="content has-text-centered">
            <h2 class="title is-3">Log in</h2>
        </header>
        <div class="card">
            <div class="card-content">
                <form role="form" method="POST" action="{{ url('/login') }}">
                    {{ csrf_field() }}

                    <label class="label">Email</label>
                    <p class="control has-icon{{ $errors->has('email') ? ' has-icon has-icon-right' : '' }}">
                        <input class="input{{ $errors->has('email') ? ' is-danger' : '' }}" type="email" placeholder="Email" name="email" value="{{ old('email') }}" required{{ !isset($errors) || count($errors) == 0 || $errors->has('email') ? ' autofocus' : '' }}>
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

                    <p class="control">
                        <label class="checkbox">
                            <input type="checkbox" name="remember">
                            Remember me
                        </label>
                    </p>
                    
                    <p class="control is-clearfix">
                        <button type="submit" class="button is-primary is-pulled-right">Log In</button>
                        <a class="button is-link" href="{{ url('/password/reset') }}">
                            Forgot Your Password?
                        </a>
                    </p>
                </form>
            </div>
            <footer class="card-footer">
                <div class="card-footer-item level">
                    <p>Don't have an account?</p>
                    <a href="/signup" class="level-right button">Sign Up</a>
                </div>
            </footer>
        </div>
    </div>
</section>
@endsection
