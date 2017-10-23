<template>
        <form
            role="form"
            @submit.prevent="onSubmit"
            @keydown="form.errors.clear($event.target.name)"
            class="section"
            id="profile"
        >
            <div class="has-max-width">
                <div class="control is-horizontal">
                    <div class="control-label">
                        <label class="label">First Name</label>
                    </div>
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
                </div>

                <div class="control is-horizontal">
                    <div class="control-label">
                        <label class="label">Last Name</label>
                    </div>
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
                </div>

                <div class="control is-horizontal">
                    <div class="control-label">
                        <label class="label">Email</label>
                    </div>
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
                </div>

                <div class="control is-horizontal">
                    <div class="control-label">
                        <label class="label">Phone</label>
                    </div>
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
                </div>

                <div class="control is-horizontal">
                    <div class="control-label">
                        <label class="label">Gender</label>
                    </div>
                    <div class="is-expanded">
                        <div class="control is-grouped is-gapless">
                            <p class="control has-icon is-expanded">
                                <label :class="['button', 'label-button', {'is-primary': form.gender == 'male'}]">
                                    <input
                                        v-model="form.gender"
                                        name="gender"
                                        type="radio"
                                        value="male"
                                        @change="form.errors.clear('gender')"
                                    >
                                    <span class="icon"><i class="fa fa-male"></i></span>
                                    <span>Male</span>
                                </label>
                            </p>
                            <p class="control has-icon is-expanded">
                                <label :class="['button', 'label-button', {'is-primary': form.gender == 'female'}]">
                                    <input
                                        v-model="form.gender"
                                        name="gender"
                                        type="radio"
                                        value="female"
                                        @change="form.errors.clear('gender')"
                                    >
                                    <span class="icon"><i class="fa fa-female"></i></span>
                                    <span>Female</span>
                                </label>
                            </p>
                        </div>
                        <template v-if="form.errors.has('gender')">
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
                            name="birthdate"
                            :class="['input', form.errors.has('birthdate') ? 'is-danger' : '']"
                            type="date"
                            placeholder="mm/dd/yyyy"
                            :autofocus="form.errors.has('birthdate')"
                            @click="form.errors.clear('birthdate')"
                        >
                        <span class="icon is-small"><i class="fa fa-calendar"></i></span>
                        <template v-if="form.errors.has('birthdate')">
                            <span class="help is-danger" v-text="form.errors.get('birthdate')"></span>
                        </template>
                    </p>
                </div>

                <div class="control is-horizontal">
                    <div class="control-label">
                        <label class="label">Height &amp; Weight</label>
                    </div>
                    <div class="control">
                        <div class="control is-grouped is-marginless">
                            <p class="control is-expanded has-addons">
                                <input
                                    v-model="form.height_feet"
                                    name="height_feet"
                                    :class="['input', form.errors.has('height_feet') ? 'is-danger' : '']"
                                    type="number"
                                    min="1"
                                    max="10"
                                    :autofocus="form.errors.has('height_feet')"
                                >
                                <span class="button">feet</span>
                            </p>
                            <p class="control is-expanded has-addons">
                                <input
                                    v-model="form.height_inches"
                                    name="height_inches"
                                    :class="['input', form.errors.has('height_inches') ? 'is-danger' : '']"
                                    type="number"
                                    min="0"
                                    max="11"
                                    :autofocus="form.errors.has('height_inches')"
                                >
                                <span class="button">inch</span>
                            </p>
                            <p class="control is-expanded has-addons">
                                <input
                                    v-model="form.weight"
                                    name="weight"
                                    :class="['input', form.errors.has('weight') ? 'is-danger' : '']"
                                    type="number"
                                    :autofocus="form.errors.has('weight')"
                                >
                                <span class="button">lbs</span>
                            </p>
                        </div>
                        <template v-if="form.errors.has('height_feet')">
                            <span class="help is-danger" v-text="form.errors.get('height_feet')"></span>
                        </template>
                        <template v-if="form.errors.has('height_inches')">
                            <span class="help is-danger" v-text="form.errors.get('height_inches')"></span>
                        </template>
                        <template v-if="form.errors.has('weight')">
                            <span class="help is-danger" v-text="form.errors.get('weight')"></span>
                        </template>
                    </div>
                </div>
            </div>

            <p v-if="includeCta" class="control is-clearfix hero-buttons">
                <button
                    type="submit"
                    class="button is-medium is-primary"
                    :disabled="form.errors.any()"
                >Save Profile</button>
            </p>
        </form>
</template>

<script>
    import {assign} from 'lodash';

    export default {
        name: 'profile',
        props: {
            form: Object,
            includeCta: Boolean
        },
        // data() {
        //     return {
        //         form: new Form({
        //             first_name: '',
        //             last_name: '',
        //             email: '',
        //             phone: '',
        //             gender: '',
        //             birthdate: '',
        //             height_feet: '',
        //             height_inches: '',
        //             weight: ''
        //         })
        //     }
        // },
        methods: {
            onSubmit() {
                this.form.submit('put', 'api/users', this.onSuccess);
            },
            onSuccess() {
                // this.$eventHub.$emit('mixpanel', "Profile Updated");
                this.$router.push('/payment');
            }
        },
        watch: {
            user() {
                assign(this.form, this.user);
            }
        },
        mounted() {
            // this.$eventHub.$emit('mixpanel', "View Profile Page");
        }
    };
</script>
