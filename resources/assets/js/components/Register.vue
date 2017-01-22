<template>
    <section class="section">
        <div class="container">
            <header class="content has-text-centered">
                <h2 class="title is-3">Create an account</h2>
            </header>
            <div class="card">
                <div class="card-content">
                    <form
                        role="form"
                        @submit.prevent="onSubmit"
                        @keydown="form.errors.clear($event.target.name)"
                    >
                        <slot></slot>

                        <label class="label">First Name</label>
                        <p class="control has-icon">
                            <input
                                v-model="form.first_name"
                                name="first_name"
                                :class="['input', form.errors.has('first_name') ? 'is-danger' : '']"
                                type="text"
                                placeholder="First Name"
                                required
                                :autofocus="!form.errors.length|| form.errors.has('first_name')"
                            >
                            <span class="icon is-small"><i class="fa fa-user-o"></i></span>
                            <template v-if="form.errors.has('first_name')">
                                <span class="icon is-small align-right is-danger"><i class="fa fa-warning"></i></span>
                                <span class="help is-danger" v-text="form.errors.get('first_name')"></span>
                            </template>
                        </p>

                        <label class="label">Last Name</label>
                        <p class="control has-icon">
                            <input
                                v-model="form.last_name"
                                name="last_name"
                                :class="['input', form.errors.has('last_name') ? 'is-danger' : '']"
                                type="text"
                                placeholder="Last Name"
                                required
                                :autofocus="form.errors.has('last_name')"
                            >
                            <span class="icon is-small"><i class="fa fa-user-o"></i></span>
                            <template v-if="form.errors.has('last_name')">
                                <span class="icon is-small align-right is-danger"><i class="fa fa-warning"></i></span>
                                <span class="help is-danger" v-text="form.errors.get('last_name')"></span>
                            </template>
                        </p>

                        <label class="label">Email</label>
                        <p class="control has-icon">
                            <input
                                v-model="form.email"
                                name="email"
                                :class="['input', form.errors.has('email') ? 'is-danger' : '']"
                                type="email"
                                placeholder="Email"
                                required
                                :autofocus="form.errors.has('email')"
                            >
                            <span class="icon is-small"><i class="fa fa-envelope"></i></span>
                            <template v-if="form.errors.has('email')">
                                <span class="icon is-small align-right is-danger align-right is-danger"><i class="fa fa-warning"></i></span>
                                <span class="help is-danger" v-text="form.errors.get('email')"></span>
                            </template>
                        </p>

                        <label class="label">Phone</label>
                        <p class="control has-icon">
                            <input
                                v-model="form.phone"
                                name="phone"
                                :class="['input', form.errors.has('phone') ? 'is-danger' : '']"
                                type="tel"
                                placeholder="Phone"
                                :autofocus="form.errors.has('phone')"
                            >
                            <span class="icon is-small"><i class="fa fa-phone"></i></span>
                            <template v-if="form.errors.has('phone')">
                                <span class="icon is-small align-right is-danger"><i class="fa fa-warning"></i></span>
                                <span class="help is-danger" v-text="form.errors.get('phone')"></span>
                            </template>
                        </p>

                        <label class="label">Password</label>
                        <p class="control has-icon">
                            <input
                                v-model="form.password"
                                name="password"
                                :class="['input', form.errors.has('password') ? 'is-danger' : '']"
                                type="password"
                                placeholder="Password"
                                required
                                :autofocus="form.errors.has('password')"
                            >
                            <span class="icon is-small"><i class="fa fa-lock"></i></span>
                            <template v-if="form.errors.has('password')">
                                <span class="icon is-small align-right is-danger"><i class="fa fa-warning"></i></span>
                                <span class="help is-danger" v-text="form.errors.get('password')"></span>
                            </template>
                        </p>

                        <label class="label">Confirm Password</label>
                        <p class="control has-icon">
                            <input
                                v-model="form.password_confirmation"
                                name="password_confirmation"
                                :class="['input', form.errors.has('password_confirmation') ? 'is-danger' : '']"
                                type="password"
                                placeholder="Confirm Password"
                                required
                                @keydown="form.errors.clear('password')"
                            >
                            <span class="icon is-small"><i class="fa fa-lock"></i></span>
                        </p>

                        <p class="control">
                            <label>
                                <input type="checkbox" v-model="agree">
                                <small>I agree to the <a href="/terms">Terms of Service</a> and <a href="/privacy">Privacy Policy</a>.</small>
                            </label>
                        </p>

                        <p class="control is-clearfix">
                            <button
                                type="submit"
                                class="button is-primary is-pulled-right"
                                :disabled="form.errors.any() || !agree"
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
</template>

<script>
    import Form from '../helpers.js';

    export default {
        props: {
            formUrl: '',
            redirectUrl: ''
        },
        data() {
            return {
                form: new Form({
                    first_name: '',
                    last_name: '',
                    email: '',
                    phone: '',
                    password: '',
                    password_confirmation: ''
                }),
                agree: true
            }
        },
        methods: {
            onSubmit() {
                this.form.submit('post', this.formUrl, this.onSuccess);
            },

            onSuccess() {
                location.href = this.redirectUrl;
                mixpanel.track("New Signup");
            }
        }
    }
</script>

<style lang="sass" scoped>
    .hero.is-primary a {
        color: #85af4a;
    }
</style>
