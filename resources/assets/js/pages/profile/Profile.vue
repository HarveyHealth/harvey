<template>
    <div class="main-container">
        <div class="main-content">
            <NotificationPopup
                    :active="notificationActive"
                    :comes-from="notificationDirection"
                    :symbol="notificationSymbol"
                    :text="notificationMessage"
            />
            <div class="main-header">
                <div class="container container-backoffice">
                    <h1 class="title header-xlarge">
                        <span class="text">Profile</span>
                    </h1>
                </div>
            </div>
            <div class="card card-info">
                <div class="card-heading-container">
                    <h2 class="card-header">Contact Info</h2>
                </div>
                <div class="card-content-container">
                    <div class="card-content-wrap">

                        <div class="error-text">
                            <p v-for="error in errorMessages">{{ error.detail }} </p>
                        </div>

                        <div class="input__container" v-if="loading">
                            Loading...
                        </div>

                        <form action="#" method="POST" class="form" id="user_form">
                            <div class="formgroups">
                                <div class="formgroup">
                                    <div class="input__container">
                                        <label class="input__label" for="first_name">First Name</label>
                                        <input class="input--text" v-model="user.attributes.first_name" type="text" name="first_name"/>
                                    </div>
                                    <div class="input__container">
                                        <label  class="input__label" for="last_name">Last Name</label>
                                        <input class="input--text" v-model="user.attributes.last_name" type="text" name="last_name"/>
                                    </div>
                                    <div class="input__container">
                                        <label  class="input__label" for="email">Email</label>
                                        <input class="input--text" v-validate="'required|email'" v-model="user.attributes.email" type="text" name="email"/>
                                        <span v-show="errors.has('email')">{{ errors.first('email') }}</span>
                                    </div>
                                    <div class="input__container">
                                        <label  class="input__label" for="phone">Phone Number</label>
                                        <input class="input--text" v-model="user.attributes.phone" type="number" name="phone"/>
                                    </div>
                                    <div class="input__container">
                                        <label  class="input__label" for="timezone">Timezone</label>
                                        <span  class="custom-select">
                                            <select name="timezone" v-model="user.attributes.timezone">
                                                <option v-for="timezone in timezones" >{{ timezone }}</option>
                                            </select>
                                        </span>
                                    </div>
                                    <div class="input__container">
                                        <label  class="input__label" for="gender">Gender</label>
                                        <div class="gender-options">
                                            <input type="radio" name="gender" id="male" v-model="user.attributes.gender" v-bind:value="'male'">
                                            <label for="male">Male</label>
                                            <input type="radio" name="gender" id="female" v-model="user.attributes.gender" v-bind:value="'female'" value="female">
                                            <label for="female">Female</label>
                                            <input type="radio" name="gender" id="other" v-model="user.attributes.gender" v-bind:value="'other'" value="other">
                                            <label for="other">Other</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="formgroup">
                                    <div class="input__container">
                                        <label class="input__label" for="address_1">Mailing Address</label>
                                        <input class="input--text" v-model="user.attributes.address_1" type="text" name="address_1"/>
                                    </div>
                                    <div class="input__container">
                                        <label class="input__label" for="address_2">Apt/Unit #</label>
                                        <input class="input--text" v-model="user.attributes.address_2" type="text" name="address_2"/>
                                    </div>
                                    <div class="input__container">
                                        <label class="input__label" for="city">City</label>
                                        <input class="input--text" v-model="user.attributes.city" type="text" name="city"/>
                                    </div>
                                    <div class="input__container">
                                        <label class="input__label" for="state">State</label>
                                        <input class="input--text" v-model="user.attributes.state" type="text" name="state"/>
                                    </div>
                                    <div class="input__container">
                                        <label class="input__label" for="zip">Zip Code</label>
                                        <input class="input--text" v-model="user.attributes.zip" type="text" name="zip"/>
                                    </div>
                                </div>
                            </div>
                            <div class="submit inline-centered">
                                <button class="button" v-on:click.prevent="submit" >Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import diff from 'object-diff';
    import _ from 'lodash';
    import timezones from '../../../../../public/timezones.json';
    import NotificationPopup from '../../commons/NotificationPopup.vue'

    export default {
        name: 'profile',
        components: {
            NotificationPopup
        },
        data() {
            return {
                loading: true,
                user: {
                    attributes: {
                        first_name: '',
                        last_name: '',
                        email: '',
                        gender: '',
                        phone: '',
                        timezone: '',
                        address_1: '',
                        address_2: '',
                        city: '',
                        state: '',
                        zip: '',
                    }
                },
                timezones: timezones,
                notificationSymbol: '&#10003;',
                notificationMessage: 'Changes Saved',
                notificationActive: false,
                notificationDirection: 'top-right',
                errorMessages: null,
            }
        },
        methods: {
            flashSuccess() {
                this.notificationActive = true;
                setTimeout(() => this.notificationActive = false, 2000);
            },
            resetErrorMessages() {
                this.errorMessages = null;
            },
            submit() {
                if(_.isEmpty(this.updates))
                    return;

                this.resetErrorMessages();

                axios.patch(`/api/v1/users/${this.user.id}`, this.updates)
                    .then(response => {
                        this.$root.$data.global.user = response.data.data;
                        this.flashSuccess();
                    })
                    .catch(err => {
                        this.errorMessages = err.response.data.errors;
                    });
            },
            getUser() {
                axios.get(`/api/v1/users/${Laravel.user.id}`)
                    .then(response => {
                        this.loading = false;
                        this.user = response.data.data;
                    })
                    .catch(error => this.user = {});
            },
        },
        mounted() {
            this.$root.$data.global.currentPage = 'profile';
        },
        created() {
            if(this.$root.$data.global.user.id) {
                this.user =_.cloneDeep(this.$root.$data.global.user);
            } else {
                this.getUser();
            }
        },
        computed: {
            updates() {
                return _.omit(diff(this.$root.$data.global.user.attributes, this.user.attributes), 'created_at', 'email_verified_at', 'phone_verified_at');
            },
        }
    }
</script>

<style>
    input, label {
        display:block;
        width: 80%;
    }

    input[type="radio"] {
        width: 20%;
    }

    .formgroups {
        display: flex;
    }

    .formgroup {
        flex: 1;
        padding: 0 20px;
    }

    .submit {
        padding-top: 20px;
        display: flex;
        justify-content: center;
    }

    .card-info {
        width: 75%;
    }

    .gender-options {
        display: flex;
        text-align: left;
    }
</style>
