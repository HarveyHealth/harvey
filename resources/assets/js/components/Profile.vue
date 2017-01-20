<template>
    <div class="container">
        <div class="hero has-text-centered">
            <div class="hero-body">
                <h1 class="title">Your doctor will need additional info</h1>
            </div>
        </div>
        <form
            role="form"
            @submit.prevent="onSubmit"
            @keydown="form.errors.clear($event.target.name)"
        >
            <div class="section">
                <div class="control is-horizontal">
                    <div class="control-label">
                        <label class="label">First Name</label>
                    </div>
                    <p class="control has-icon">
                        <input
                            v-model="form.first_name"
                            :class="['input', form.errors.has('first_name') ? 'is-danger' : '']"
                            type="text"
                            placeholder="First Name"
                            name="first_name"
                            :value.literal="user.first_name"
                            required
                            :autofocus="!form.errors.length|| form.errors.has('first_name')"
                        >
                        <span class="icon is-small"><i class="fa fa-user-o"></i></span>
                        <template v-if="form.errors.has('first_name')">
                            <span class="icon is-small align-right is-danger"><i class="fa fa-warning"></i></span>
                            <span class="help is-danger" v-text="form.errors.get('first_name')"></span>
                        </template>
                    </p>
                </div>

                <div class="control is-horizontal">
                    <div class="control-label">
                        <label class="label">Last Name</label>
                    </div>
                    <p class="control has-icon">
                        <input
                            v-model="form.last_name"
                            :class="['input', form.errors.has('last_name') ? 'is-danger' : '']"
                            type="text"
                            placeholder="Last Name"
                            name="last_name"
                            required
                            :autofocus="form.errors.has('last_name')"
                        >
                        <span class="icon is-small"><i class="fa fa-user-o"></i></span>
                        <template v-if="form.errors.has('last_name')">
                            <span class="icon is-small align-right is-danger"><i class="fa fa-warning"></i></span>
                            <span class="help is-danger" v-text="form.errors.get('last_name')"></span>
                        </template>
                    </p>
                </div>
                
                <div class="control is-horizontal">
                    <div class="control-label">
                        <label class="label">Email</label>
                    </div>
                    <p class="control has-icon">
                        <input
                            v-model="form.email"
                            :class="['input', form.errors.has('email') ? 'is-danger' : '']"
                            type="email"
                            placeholder="Email"
                            name="email"
                            required
                            :autofocus="form.errors.has('email')"
                        >
                        <span class="icon is-small"><i class="fa fa-envelope"></i></span>
                        <template v-if="form.errors.has('email')">
                            <span class="icon is-small align-right is-danger align-right is-danger"><i class="fa fa-warning"></i></span>
                            <span class="help is-danger" v-text="form.errors.get('email')"></span>
                        </template>
                    </p>
                </div>
                
                <div class="control is-horizontal">
                    <div class="control-label">
                        <label class="label">Phone</label>
                    </div>
                    <p class="control has-icon">
                        <input
                            v-model="form.phone"
                            :class="['input', form.errors.has('phone') ? 'is-danger' : '']"
                            type="tel"
                            placeholder="Phone"
                            name="phone"
                            :autofocus="form.errors.has('phone')"
                        >
                        <span class="icon is-small"><i class="fa fa-phone"></i></span>
                        <template v-if="form.errors.has('phone')">
                            <span class="icon is-small align-right is-danger"><i class="fa fa-warning"></i></span>
                            <span class="help is-danger" v-text="form.errors.get('phone')"></span>
                        </template>
                    </p>
                </div>

                <div class="control is-horizontal">
                    <div class="control-label">
                        <label class="label">Gender</label>
                    </div>
                    <div class="control is-grouped is-gapless">
                        <p class="control has-icon is-expanded">
                            <label :class="['button', 'label-button', {'is-primary': form.gender == 'male'}]">
                                <input
                                    v-model="form.gender"
                                    :class="[form.errors.has('gender') ? 'is-danger' : '']"
                                    type="radio"
                                    name="gender"
                                    value="male"
                                    :autofocus="form.errors.has('gender')"
                                >
                                <span class="icon"><i class="fa fa-male"></i></span>
                                <span>Male</span>
                            </label>
                        </p>
                        <p class="control has-icon is-expanded">
                            <label :class="['button', 'label-button', {'is-primary': form.gender == 'female'}]">
                                <input
                                    v-model="form.gender"
                                    :class="[form.errors.has('gender') ? 'is-danger' : '']"
                                    type="radio"
                                    name="gender"
                                    value="female"
                                    :autofocus="form.errors.has('gender')"
                                >
                                <span class="icon"><i class="fa fa-female"></i></span>
                                <span>Female</span>
                            </label>
                        </p>
                        <template v-if="form.errors.has('gender')">
                            <span class="icon is-small align-right is-danger"><i class="fa fa-warning"></i></span>
                            <span class="help is-danger" v-text="form.errors.get('gender')"></span>
                        </template>
                    </div>
                </div>

                <div class="control is-horizontal">
                    <div class="control-label">
                        <label class="label">Birthdate</label>
                    </div>
                    <p class="control has-icon">
                        <input
                            v-model="form.birthdate"
                            :class="['input', form.errors.has('birthdate') ? 'is-danger' : '']"
                            type="date"
                            placeholder="mm/dd/yyyy"
                            name="birthdate"
                            :autofocus="form.errors.has('birthdate')"
                        >
                        <span class="icon is-small"><i class="fa fa-calendar"></i></span>
                        <template v-if="form.errors.has('birthdate')">
                            <span class="icon is-small align-right is-danger"><i class="fa fa-warning"></i></span>
                            <span class="help is-danger" v-text="form.errors.get('birthdate')"></span>
                        </template>
                    </p>
                </div>

                <div class="control is-horizontal">
                    <div class="control-label">
                        <label class="label">Height &amp; Weight</label>
                    </div>
                    <div class="control is-grouped">
                        <p class="control is-expanded has-addons">
                            <input
                                v-model="form.height"
                                :class="['input', form.errors.has('height') ? 'is-danger' : '']"
                                type="text"
                                name="height"
                                :autofocus="form.errors.has('height')"
                            >
                            <span class="button">feet</span>
                        </p>
                        <p class="control is-expanded has-addons">
                            <input
                                v-model="form.height"
                                :class="['input', form.errors.has('height') ? 'is-danger' : '']"
                                type="text"
                                name="height"
                                :autofocus="form.errors.has('height')"
                            >
                            <span class="button">inch</span>
                        </p>
                        <p class="control is-expanded has-addons">
                            <input
                                v-model="form.weight"
                                :class="['input', form.errors.has('weight') ? 'is-danger' : '']"
                                type="text"
                                name="weight"
                                :autofocus="form.errors.has('weight')"
                            >
                            <span class="button">lbs</span>
                            <template v-if="form.errors.has('weight')">
                                <span class="icon is-small align-right is-danger"><i class="fa fa-warning"></i></span>
                                <span class="help is-danger" v-text="form.errors.get('weight')"></span>
                            </template>
                        </p>
                        <template v-if="form.errors.has('height') || form.errors.has('weight')">
                            <span class="icon is-small align-right is-danger"><i class="fa fa-warning"></i></span>
                            <span class="help is-danger" v-text="form.errors.get('height')"></span>
                            <span class="help is-danger" v-text="form.errors.get('weight')"></span>
                        </template>
                    </div>
                </div>
            </div>
                
            <p class="control is-clearfix hero-buttons">
                <button
                    type="submit"
                    class="button is-medium is-primary"
                    :disabled="form.errors.any()"
                >Save Profile</button>
            </p>
        </form>
    </div>
</template>

<script>
    import Form from '../helpers.js';

    export default {
        name: 'profile',
        props: ['user'],
        data() {
            return {
                form: new Form({
                    first_name: '',
                    last_name: '',
                    email: '',
                    phone: '',
                    gender: '',
                    birthdate: '',
                    height: '',
                    weight: ''
                })
            }
        },
        methods: {
            onSubmit() {
                this.form.submit('put', 'api/users', this.onSuccess);
            },
            onSuccess() {
                mixpanel.track("Profile Updated");
                this.$router.push('/payment');
            }
        },
        watch: {
            user() {
                Object.assign(this.form, this.user);
            }
        },
        mounted() {
            mixpanel.track("View Profile Page");
        }
    }
</script>

<style lang="sass" scoped>
    .section > .control {
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }
    .control.has-addons {
        .input {
            width: 100%;
        }
        .button {
            cursor: default;
        }
    }
    .label-button {
        width: 100%;
        input[type="radio"] {
            display: none;
        }
    }
</style>