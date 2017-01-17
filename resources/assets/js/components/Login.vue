<template>
    <section class="section">
        <div class="container">
            <header class="content has-text-centered">
                <h2 class="title is-3">Log in</h2>
            </header>
            <div class="card">
                <div class="card-content">
                    <form
                        role="form"
                        method="POST"
                        :action="formUrl"
                        @submit.prevent="onSubmit"
                        @keydown="form.errors.clear($event.target.name)"
                    >
                        <slot name="csrf_field"></slot>

                        <label class="label">Email</label>
                        <p class="control has-icon">
                            <input
                                v-model="form.email"
                                :class="['input', form.errors.has('email') ? 'is-danger' : '']"
                                type="email"
                                placeholder="Email"
                                name="email"
                                :value="old.email"
                                required
                                :autofocus="!form.errors.length|| form.errors.has('email')"
                            >
                            <span class="icon is-small"><i class="fa fa-envelope"></i></span>
                            <template v-if="form.errors.has('email')">
                                <span class="icon is-small align-right is-danger"><i class="fa fa-warning"></i></span>
                                <span class="help is-danger" v-text="form.errors.get('email')"></span>
                            </template>
                        </p>

                        <label class="label">Password</label>
                        <p class="control has-icon">
                            <input
                                v-model="form.password"
                                :class="['input', form.errors.has('password') ? 'is-danger' : '']"
                                type="password"
                                placeholder="Password"
                                name="password"
                                :value="old.password"
                                required
                                :autofocus="form.errors.has('password')"
                            >
                            <span class="icon is-small"><i class="fa fa-lock"></i></span>
                            <template v-if="form.errors.has('password')">
                                <span class="icon is-small align-right is-danger"><i class="fa fa-warning"></i></span>
                                <span class="help is-danger" v-text="form.errors.get('password')"></span>
                            </template>
                        </p>

                        <p class="control">
                            <label class="checkbox">
                                <input
                                    v-model="form.remember"
                                    type="checkbox"
                                    name="remember"
                                >
                                Remember me
                            </label>
                        </p>
                        
                        <p class="control is-clearfix">
                            <button
                                type="submit"
                                class="button is-primary is-pulled-right"
                                :disabled="form.errors.any()"
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
</template>

<script>
    import Form from '../helpers.js';

    export default {
        props: {
            formUrl: '',
            old: {}
        },
        data() {
            return {
                form: new Form({
                    email: '',
                    password: '',
                    remember: false,
                })
            }
        },
        methods: {
            onSubmit() {
                this.form.submit('post', this.formUrl, this.onSuccess);
            },

            onSuccess() {
                location.href = '/dashboard';
            }
        }
    }
</script>