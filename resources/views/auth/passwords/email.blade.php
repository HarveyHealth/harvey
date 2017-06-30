@extends('legacy._layouts.public')

@section('page_title','Request Password Reset')

@section('content')
<div class="window-size grey_bg">
    <div class="header nav">
          <div class="container">
              <div class="nav-left">
                  <a href="/" class="nav-item">
                      <div class="logo-wrapper">
                          <svg xmlns="http://www.w3.org/2000/svg" class="logo" viewBox="0 0 248.84 63.91">
                            <path class="harvey-icon" fill="#B4E7A0" d="M39.68 49.97a20.85 20.85 0 0 1-7 .93 60.32 60.32 0 0 1-7-.46c2.09-2.58 7.71-9.2 11.72-10.72a11.4 11.4 0 0 1 4-.83c2.36 0 3.94 1.1 4.43 3.11.77 3.15-1.61 6.27-6.08 8M23.5 2.03h.16c2.7 0 4.55 2.92 4.71 7.43.15 4.11-3.19 11-4.66 13.79-1.36-2.75-4.38-9.3-4.53-13.41-.17-4.63 1.53-7.7 4.33-7.81m-8.22 48.88a20.85 20.85 0 0 1-7-.93c-4.47-1.69-6.85-4.82-6.08-8 .49-2 2.07-3.11 4.43-3.11a11.41 11.41 0 0 1 4 .83c4 1.52 9.64 8.14 11.73 10.72a60.31 60.31 0 0 1-7 .46M9.99 31.5c-3.6-2.56-5-5.91-3.6-8.53a3.49 3.49 0 0 1 3.24-1.93 8.7 8.7 0 0 1 4.79 1.87c3.22 2.29 6.7 9.43 8 12.3-2.78-.38-9.27-1.47-12.43-3.71m23.19-8.59a8.7 8.7 0 0 1 4.79-1.87 3.49 3.49 0 0 1 3.24 1.93c1.42 2.63 0 6-3.6 8.53-3.16 2.24-9.65 3.33-12.43 3.71 1.31-2.87 4.78-10 8-12.3m14.06 18.73c-.66-2.67-2.86-4.27-5.9-4.27a12.81 12.81 0 0 0-4.5.93c-4.34 1.64-10 8.28-12.24 11V36.84c2.26-.25 10.05-1.34 13.9-4.09 4.27-3 5.86-7.15 4.05-10.5a5 5 0 0 0-4.57-2.75 10.12 10.12 0 0 0-5.65 2.15c-3 2.14-6.22 7.8-7.74 11.29v-8c.76-1.79 5.53-10.36 5.34-15.53C29.7 2.83 26.42.5 23.7.5h-.2c-2.82.11-6 2.64-5.77 9.39.2 5.59 5.36 14.79 5.36 15.33v7.74c-1.52-3.49-4.75-9.15-7.77-11.29a10.18 10.18 0 0 0-5.68-2.17 5 5 0 0 0-4.57 2.73c-1.81 3.35-.2 7.47 4.07 10.5 3.85 2.77 11.66 3.86 13.93 4.11v12.12c-2.27-3-7.81-9.09-12-10.65a12.92 12.92 0 0 0-4.52-.93c-3 0-5.25 1.6-5.9 4.27-1 3.94 1.79 7.77 7 9.75a22.33 22.33 0 0 0 7.6 1 63.81 63.81 0 0 0 7.77-.54v10.79a.76.76 0 1 0 1.52 0v-10.8a55.16 55.16 0 0 0 8.06.58 22.2 22.2 0 0 0 7.58-1c5.23-2 8-5.81 7-9.75"></path>
                            <path class="harvey-text" fill="#5F7278" d="M69.3 30.5h15.61V16.31h5.46V52.5h-5.46V35.6H69.3v16.9h-5.46V16.31h5.46zM120.78 43.71h-15.5l-4 8.79h-5.91l17.91-38.46 17.27 38.46h-6zm-2.23-5.13l-5.37-12.3-5.62 12.3zM147.81 37.08l11.2 15.42h-6.68L142 37.69h-1V52.5h-5.46V16.31h6.4q7.17 0 10.36 2.7a9.9 9.9 0 0 1 3.52 7.92 10.28 10.28 0 0 1-2.2 6.61 9.82 9.82 0 0 1-5.81 3.54zm-6.8-4.15h1.73q7.76 0 7.76-5.93 0-5.55-7.55-5.55H141zM165.5 16.31l10 25.08 10.15-25.08h6l-16.29 38.91-15.86-38.91zM216.55 21.45h-14.51v8.7h14.09v5.13h-14.09v12.09h14.51v5.13h-20V16.31h20zM231.5 36.87l-11.79-20.56h6.28l8.27 14.46 8.3-14.46h6.28l-11.86 20.56V52.5h-5.48z"></path>
                        </svg>
                      </div>
                  </a>
              </div>
              <div class="nav-right">
                  <span class="nav-item">
                      <a href="tel:800-690-9989" class="button is-primary is-outlined">(800) 690-9989</a>
                      <a href="/login" class="button is-primary is-outlined is-hidden-mobile">Log In</a>
                  </span>
              </div>
          </div>
      </div>
    <section class="signup-content">
        <div class="container login-width large-top-margin">
            <h1 class="header-xlarge login-heading">Reset your password</h1>
            <form role="form" method="POST" action="{{ url('/password/email') }}">
            <div class="card card-padding">
                <div class="card-section">
                    @if (session('status'))
                        <div class="notification is-success">
                            <button class="delete"></button>
                            {{ session('status') }}
                        </div>
                    @endif

                        {{ csrf_field() }}

                        <div class="input-wrap">
                            <input class="input{{ $errors->has('email') ? ' is-danger' : '' }} input login-input" type="email" placeholder="Email" name="email" value="{{ old('email') }}" required autofocus>
                            @if ($errors->has('email'))
                                <span class="help is-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>

                        <p class="reset-p">Enter your email below and we will send you a link to reset your password.</p>
    
                    </div>
                </div>
                <button type="submit" class="button is-primary login-buttons login-top-margin reset-width">Reset your password</button>               
            </form>
        </div>
    </section>
</div>
@endsection
