@extends('legacy._layouts.public')

@section('page_title','Log in')

@section('main_content')
<section class="signup-content grey_bg">
    <div class="container login-width large-top-margin">
        <h1 class="header-xlarge login-heading">Log in to your account</h1>
                <form
                    id="login"
                    role="form"
                    method="post"
                    action="/login"
                    redirect-url="/#/"
                    @submit.prevent.self="onSubmit"
                    @keydown="login.form.errors.clear($event.target.name)"
                >
                <div class="card card-padding">
                <div class="card-section">
                    {{ csrf_field() }}

                    <div class="input-wrap">
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
                        <a class="button is-link" href="/password/reset">
                            Forgot Your Password?
                        </a>
                    </p>
            </div>
        </div>
        <footer class="card-footer">
            <div class="card-footer-item level">
                <a href="/signup"><button href="/signup" class="button is-pulled-right login-buttons">Sign Up</button></a>
                <button
                    type="submit"
                    class="button is-primary login-buttons"
                >Log In</button>
            </div>
        </footer>
       </form>
    </div>
</section>
@endsection
