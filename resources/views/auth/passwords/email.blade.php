@extends('legacy._layouts.public')

@section('page_title','Request Password Reset')

@section('content')
<section class="section">
    <div class="container">
        <header class="content has-text-centered">
            <h2 class="title is-3">Reset Password</h2>
        </header>
        <div class="card">
            <div class="card-content">
                @if (session('status'))
                    <div class="notification is-success">
                        <button class="delete"></button>
                        {{ session('status') }}
                    </div>
                @endif

                <form role="form" method="POST" action="{{ secure_url('/password/email') }}">
                    {{ csrf_field() }}

                    <label class="label">Email</label>
                    <p class="control has-icon{{ $errors->has('email') ? ' has-icon has-icon-right' : '' }}">
                        <input class="input{{ $errors->has('email') ? ' is-danger' : '' }}" type="email" placeholder="Email" name="email" value="{{ old('email') }}" required autofocus>
                        <span class="icon is-small"><i class="fa fa-envelope"></i></span>
                        @if ($errors->has('email'))
                            <span class="icon is-small"><i class="fa fa-warning"></i></span>
                            <span class="help is-danger">{{ $errors->first('email') }}</span>
                        @endif
                    </p>

                    <p class="control is-clearfix">
                        <button type="submit" class="button is-primary is-pulled-right">Send Password Reset Link</button>
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
