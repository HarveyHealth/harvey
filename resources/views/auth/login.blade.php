@extends('legacy._layouts.public')

@section('page_title','Log in')

@section('main_content')
<section class="signup-content" style="background-color: rgba(119,119,116, 0.08); width: 100%; height: 100%;">
    <div class="container small" style="width: 600px;">
        <img src="/images/signup/tree.png" class="registration-tree">
        <h1 class="header-xlarge" style="font-size: 32px; font-weight: 500; text-align: center; color: #777777; line-height: 1.3; margin-bottom: 15px;">Log in</h1>
            <div class="card" style="padding: 50px 90px;">
                <div class="card-section">
                <form
                    id="login"
                    role="form"
                    method="post"
                    action="/login"
                    redirect-url="/#/"
                    @submit.prevent.self="onSubmit"
                    @keydown="login.form.errors.clear($event.target.name)"
                >
                    {{ csrf_field() }}

                    <div class="input-wrap">
                        <input
                            v-model="login.form.email"
                            name="email"
                            class="input"
                            :class="{'is-danger' : login.form.errors.has('email')}"
                            type="email"
                            placeholder="Email"
                            required
                            :autofocus="!login.form.errors.length || login.form.errors.has('email')"
                            @change="login.form.errors.clear()"
                        >
                        <span class="icon is-small"><i class="fa fa-envelope"></i></span>
                        <template v-if="login.form.errors.has('email')">
                            <span class="icon is-small align-right is-danger"><i class="fa fa-warning"></i></span>
                            <span class="help is-danger" v-text="login.form.errors.get('email')"></span>
                        </template>
                    </div>

                    <div class="input-wrap">
                        <input
                            v-model="login.form.password"
                            name="password"
                            class="input"
                            :class="{'is-danger' : login.form.errors.has('password')}"
                            type="password"
                            placeholder="Password"
                            required
                            :autofocus="login.form.errors.has('password')"
                            @change="login.form.errors.clear()"
                        >
                        <span class="icon is-small"><i class="fa fa-lock"></i></span>
                        <template v-if="login.form.errors.has('password')">
                            <span class="icon is-small align-right is-danger"><i class="fa fa-warning"></i></span>
                            <span class="help is-danger" v-text="login.form.errors.get('password')"></span>
                        </template>
                    </div>

                    <p class="control">
                        <label class="checkbox">
                            <input
                                v-model="login.form.remember"
                                type="checkbox"
                            >
                            Remember me
                        </label>
                    </p>

                    <p class="control is-clearfix">
                        <button
                            type="submit"
                            class="button is-primary is-pulled-right"
                        >Log In</button>
                        <a class="button is-link" href="/password/reset">
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
