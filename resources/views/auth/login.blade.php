@extends('legacy._layouts.public')

@section('page_title','Log in')

@section('content')
<section class="section">
    <div class="container">
        <header class="content has-text-centered">
            <h2 class="title is-3">Log in</h2>
        </header>
        <div class="card">
            <div class="card-content">
                <form
                    id="login"
                    role="form"
                    method="post"
                    action="/login"
                    redirect-url="/dashboard"
                    @submit.prevent.self="onSubmit"
                    @keydown="login.form.errors.clear($event.target.name)"
                >
                    {{ csrf_field() }}

                    <label class="label">Email</label>
                    <p class="control has-icon">
                        <input
                            v-model="login.form.email"
                            name="email"
                            class="input"
                            :class="{'is-danger' : login.form.errors.has('email')}"
                            type="email"
                            placeholder="Email"
                            required
                            :autofocus="!login.form.errors.length || login.form.errors.has('email')"
                        >
                        <span class="icon is-small"><i class="fa fa-envelope"></i></span>
                        <template v-if="login.form.errors.has('email')">
                            <span class="icon is-small align-right is-danger"><i class="fa fa-warning"></i></span>
                            <span class="help is-danger" v-text="login.form.errors.get('email')"></span>
                        </template>
                    </p>

                    <label class="label">Password</label>
                    <p class="control has-icon">
                        <input
                            v-model="login.form.password"
                            name="password"
                            class="input"
                            :class="{'is-danger' : login.form.errors.has('password')}"
                            type="password"
                            placeholder="Password"
                            required
                            :autofocus="login.form.errors.has('password')"
                        >
                        <span class="icon is-small"><i class="fa fa-lock"></i></span>
                        <template v-if="login.form.errors.has('password')">
                            <span class="icon is-small align-right is-danger"><i class="fa fa-warning"></i></span>
                            <span class="help is-danger" v-text="login.form.errors.get('password')"></span>
                        </template>
                    </p>

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
                            :disabled="login.form.errors.any()"
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