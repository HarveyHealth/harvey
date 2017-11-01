@extends('legacy._layouts.public')
@section('page_title','Reset Password')
@section('page_name','Reset Password')
@section('main_content')

<section class="page-content">
    <div class="container login-width large-top-margin">
        <div class="logo-wrapper">
            <a href="/">
                {!! $svgImages['logo'] !!}
            </a>
        </div>
        <form role="form" method="POST" action="{{ url('/password/email') }}" @submit="isProcessing = true">
          <div class="card card-padding">
            <div class="card-section">
              @if (session('status'))
                  <div class="notification green-color">
                      <button class="delete"></button>
                      {{ session('status') }}
                  </div>
              @endif
              {{ csrf_field() }}
              <p class="reset-p">Enter your email address below and we will send you a link to reset your password.</p>
              <div class="input-wrap">
                  <label class="hoverInput">Email</label>
                  <input class="input{{ $errors->has('email') ? ' is-danger' : '' }} input login-input" type="email" placeholder="Email" name="email" value="{{ old('email') }}" required autofocus>
                  @if ($errors->has('email'))
                      <span class="help is-danger">{{ $errors->first('email') }}</span>
                  @endif
              </div>
            </div>
          </div>
          <footer class="card-footer">
              <div class="card-footer-item level">
                  <a href="/login" class="button login-buttons">Cancel</button></a>
                  <button type="submit" class="button is-primary login-buttons">
                    <span v-if="!isProcessing">Send Link</span>
                    <LoadingGraphic v-else :size="12" />
                  </button>
              </div>
          </footer>
        </form>
    </div>
</section>

@endsection
