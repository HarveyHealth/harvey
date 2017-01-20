<template>
    <section class="section">
        <div class="container">
            <header class="content has-text-centered">
                <h2 class="title is-3">Payment Page</h2>
            </header>
            <div class="card">
                <div class="card-content">
                    <!-- <form
                        role="form"
                        method="POST"
                        :action="formUrl"
                        @submit.prevent="onSubmit"
                        @keydown="form.errors.clear($event.target.name)"
                    >
                        <slot></slot>

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
                    </form> -->
                </div>
                <footer class="card-footer">
                </footer>
            </div>
        </div>
    </section>
</template>

<script>
    import Form from '../helpers.js';

    export default {
        name: 'payment',
        props: ['user'],
        mounted() {
            mixpanel.track("View Payments Page");
        }
    }
</script>