@extends('_layouts.public', ['view' => 'register-view'])

@section('page_title', 'Sign Up')

@if (App::environment('production', 'staging'))
    @push('square')
        <script src='https://squareup.com/appointments/buyer/widget/c64ea9cf-ffea-45c8-a153-d0dd72234c4c.js'></script>
    @endpush
@else
    @section('content')
    <section class="section">
        <div class="container">
            <header class="content has-text-centered">
                <h2 class="title is-3">Create your account</h2>
            </header>
            <div class="card">
                <div class="card-content">
                    <form
                        id="register"
                        role="form"
                        method="post"
                        action="{{ secure_url('/register') }}"
                        redirect-url="{{ secure_url('/dashboard#/new-appointment') }}"
                        @submit.prevent="onSubmit"
                        @keydown="register.form.errors.clear($event.target.name)"
                    >
                        {{ csrf_field() }}

                        <label class="label">First Name</label>
                        <p class="control has-icon">
                            <input
                                v-model="register.form.first_name"
                                name="first_name"
                                class="input"
                                :class="{'is-danger' : register.form.errors.has('first_name')}"
                                type="text"
                                placeholder="First Name"
                                required
                                :autofocus="!register.form.errors.length|| register.form.errors.has('first_name')"
                            >
                            <span class="icon is-small"><i class="fa fa-user-o"></i></span>
                            <template v-if="register.form.errors.has('first_name')">
                                <span class="icon is-small align-right is-danger"><i class="fa fa-warning"></i></span>
                                <span class="help is-danger" v-text="register.form.errors.get('first_name')"></span>
                            </template>
                        </p>

                        <label class="label">Last Name</label>
                        <p class="control has-icon">
                            <input
                                v-model="register.form.last_name"
                                name="last_name"
                                class="input"
                                :class="{'is-danger' : register.form.errors.has('last_name')}"
                                type="text"
                                placeholder="Last Name"
                                required
                                :autofocus="register.form.errors.has('last_name')"
                            >
                            <span class="icon is-small"><i class="fa fa-user-o"></i></span>
                            <template v-if="register.form.errors.has('last_name')">
                                <span class="icon is-small align-right is-danger"><i class="fa fa-warning"></i></span>
                                <span class="help is-danger" v-text="register.form.errors.get('last_name')"></span>
                            </template>
                        </p>

                        <label class="label">Email</label>
                        <p class="control has-icon">
                            <input
                                v-model="register.form.email"
                                name="email"
                                class="input"
                                :class="{'is-danger' : register.form.errors.has('email')}"
                                type="email"
                                placeholder="Email"
                                required
                                :autofocus="register.form.errors.has('email')"
                            >
                            <span class="icon is-small"><i class="fa fa-envelope"></i></span>
                            <template v-if="register.form.errors.has('email')">
                                <span class="icon is-small align-right is-danger align-right is-danger"><i class="fa fa-warning"></i></span>
                                <span class="help is-danger" v-text="register.form.errors.get('email')"></span>
                            </template>
                        </p>

                        <label class="label">Phone</label>
                        <p class="control has-icon">
                            <input
                                v-model="register.form.phone"
                                name="phone"
                                class="input"
                                :class="{'is-danger' : register.form.errors.has('phone')}"
                                type="tel"
                                placeholder="Phone"
                                :autofocus="register.form.errors.has('phone')"
                            >
                            <span class="icon is-small"><i class="fa fa-phone"></i></span>
                            <template v-if="register.form.errors.has('phone')">
                                <span class="icon is-small align-right is-danger"><i class="fa fa-warning"></i></span>
                                <span class="help is-danger" v-text="register.form.errors.get('phone')"></span>
                            </template>
                        </p>

                        <label class="label">Password</label>
                        <p class="control has-icon">
                            <input
                                v-model="register.form.password"
                                name="password"
                                class="input"
                                :class="{'is-danger' : register.form.errors.has('password')}"
                                type="password"
                                placeholder="Password"
                                required
                                :autofocus="register.form.errors.has('password')"
                            >
                            <span class="icon is-small"><i class="fa fa-lock"></i></span>
                            <template v-if="register.form.errors.has('password')">
                                <span class="icon is-small align-right is-danger"><i class="fa fa-warning"></i></span>
                                <span class="help is-danger" v-text="register.form.errors.get('password')"></span>
                            </template>
                        </p>

                        <p class="control">
                            <label>
                                <input type="checkbox" name="terms" v-model="register.form.terms">
                                <small>I agree to the <a href="/terms" target="_blank">Terms of Service</a> and <a href="/privacy" target="_blank">Privacy Policy</a>.</small>
                            </label>
                        </p>

                        <p class="control is-clearfix">
                            <button
                                type="submit"
                                class="button is-primary is-pulled-right"
                                :disabled="register.form.errors.any() || !register.form.terms"
                            >Sign Up</button>
                        </p>
                    </form>
                </div>
                <footer class="card-footer">
                    <div class="card-footer-item level">
                        <p>Already have an account?</p>
                        <a href="/login" class="level-right button">Log in</a>
                    </div>
                </footer>
            </div>
        </div>
    </section>
    @endsection
@endif
