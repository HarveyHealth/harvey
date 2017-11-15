@extends('legacy._layouts.public')
@section('page_title','Log In')
@section('main_content')

<section class="signup-content">
    <div class="container login-width large-top-margin">
        <div class="logo-wrapper">
            <a href="/">
                {!! $svgImages['logo'] !!}
            </a>
        </div>
        <form
            id="login"
            role="form"
            method="post"
            action="/login"
            redirect-url="/dashboard"
            @submit.prevent.self="onSubmit"
            @keydown="login.form.errors.clear($event.target.name)"
        >

            <div class="card card-padding">
                <div class="card-section">
                    {{ csrf_field() }}
                    <div class="input-wrap">
                        <label :class="{typed: login.form.email}" class="hoverInput">Email</label>
                        <input
                            v-model="login.form.email"
                            name="email"
                            class="input login-input"
                            :class="{'is-danger' : login.form.errors.has('email')}"
                            type="email"
                            placeholder="Email"
                            required
                            :autofocus="!login.form.errors.length || login.form.errors.has('email')"
                            @change="login.form.errors.clear()"
                        >
                        <template v-if="login.form.errors.has('email')">
                            <span class="icon is-small align-right is-danger"><i class="fa fa-warning"></i></span>
                            <span class="help is-danger" v-text="login.form.errors.get('email')"></span>
                        </template>
                    </div>
                    <div class="input-wrap">
                        <label :class="{typed: login.form.password}" class="hoverInput">Password</label>
                        <input
                            v-model="login.form.password"
                            name="password"
                            class="input login-input"
                            :class="{'is-danger' : login.form.errors.has('password')}"
                            type="password"
                            placeholder="Password"
                            required
                            :autofocus="login.form.errors.has('password')"
                            @change="login.form.errors.clear()"
                        >
                        <template v-if="login.form.errors.has('password')">
                            <span class="icon is-small align-right is-danger"><i class="fa fa-warning"></i></span>
                            <span class="help is-danger" v-text="login.form.errors.get('password')"></span>
                        </template>
                    </div>
                    <div class="input-wrap">
                        <p class="control remember-me is-hidden-mobile">
                            <label class="checkbox">
                                <input
                                    v-model="login.form.remember"
                                    type="checkbox"
                                >Remember me
                            </label>
                        </p>
                        <p class="control forgot-password is-clearfix">
                            <a class="button is-link" href="/password/reset">
                                Forgot Your Password?
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <footer class="card-footer">
                <div class="card-footer-item level">
                    <a href="/get-started" class="button login-buttons">Sign Up</a>
                    <button type="submit" class="button is-primary login-buttons">
                      <span v-if="!isProcessing">Log In</span>
                      <loading-graphic v-else :size="12" />
                    </button>
                </div>
            </footer>

            <div class="font-centered space-children-lg">
              <div class="Divider-text is-gray" data-text="OR"></div>
              <facebook-signin :type="'login'" :on-click="facebookLogin" />
            </div>

       </form>
    </div>
</section>
@endsection
