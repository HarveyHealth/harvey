@extends('_layouts.public')

@section('page_title','Sign Up')

@section('content')
<section class="section">
    <div class="container">
        <div class="columns is-mobile">
            <div class="column is-6-tablet is-offset-3-tablet is-4-desktop is-offset-4-desktop">
                <div class="card">
                    <header class="card-content has-text-centered">
                        <h2 class="title is-3">Welcome to {{ config('app.name') }}</h2>
                        <p class="subtitle is-5">Create an account</p>
                    </header>
                    <div class="card-content">
                        <form role="form" method="POST" action="{{ url('/register') }}">
                            {{ csrf_field() }}

                            <label class="label">First Name</label>
                            <p class="control{{ $errors->has('first_name') ? ' has-icon has-icon-right' : '' }}">
                                <input class="input{{ $errors->has('first_name') ? ' is-danger' : '' }}" type="text" placeholder="First Name" name="first_name" value="{{ old('first_name') }}" required autofocus>
                                @if ($errors->has('first_name'))
                                    <span class="icon is-small"><i class="fa fa-warning"></i></span>
                                    <span class="help is-danger">{{ $errors->first('first_name') }}</span>
                                @endif
                            </p>

                            <label class="label">Last Name</label>
                            <p class="control{{ $errors->has('last_name') ? ' has-icon has-icon-right' : '' }}">
                                <input class="input{{ $errors->has('last_name') ? ' is-danger' : '' }}" type="text" placeholder="Last Name" name="last_name" value="{{ old('last_name') }}" required>
                                @if ($errors->has('last_name'))
                                    <span class="icon is-small"><i class="fa fa-warning"></i></span>
                                    <span class="help is-danger">{{ $errors->first('last_name') }}</span>
                                @endif
                            </p>
                            
                            <label class="label">Email</label>
                            <p class="control has-icon{{ $errors->has('email') ? ' has-icon has-icon-right' : '' }}">
                                <input class="input{{ $errors->has('email') ? ' is-danger' : '' }}" type="email" placeholder="Email" name="email" value="{{ old('email') }}" required>
                                <span class="icon is-small"><i class="fa fa-envelope"></i></span>
                                @if ($errors->has('email'))
                                    <span class="icon is-small"><i class="fa fa-warning"></i></span>
                                    <span class="help is-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </p>
                            
                            <label class="label">Phone</label>
                            <p class="control has-icon{{ $errors->has('phone') ? ' has-icon has-icon-right' : '' }}">
                                <input class="input{{ $errors->has('phone') ? ' is-danger' : '' }}" type="tel" placeholder="Phone" name="phone" value="{{ old('phone') }}" required>
                                <span class="icon is-small"><i class="fa fa-phone"></i></span>
                                @if ($errors->has('phone'))
                                    <span class="icon is-small"><i class="fa fa-warning"></i></span>
                                    <span class="help is-danger">{{ $errors->first('phone') }}</span>
                                @endif
                            </p>
                            
                            <label class="label">Password</label>
                            <p class="control has-icon{{ $errors->has('password') ? ' has-icon has-icon-right' : '' }}">
                                <input class="input{{ $errors->has('password') ? ' is-danger' : '' }}" type="password" placeholder="Password" name="password" required>
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
                            
                            <p class="control">
                                <label>
                                    <input type="checkbox" checked>
                                    <small>By clicking Sign Up, you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.</small>
                                </label>
                            </p>

                            <p class="control is-clearfix">
                                <button type="submit" class="button is-primary is-pulled-right">Sign Up</button>
                            </p>
                        </form>
                    </div>
                    <footer class="card-footer">
                        <div class="card-footer-item level">
                            <p class="level-left">Already have a {{ config('app.name') }} account?</p>
                            <a href="/login" class="level-right button">Log in</a>
                        </div>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
