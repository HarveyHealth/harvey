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
                        <span class="text">Profile for User #{{ $route.params.id }}</span>
                    </h1>
                </div>
            </div>
            <div class="card card-info">
                <div class="card-heading-container">
                    <h2 class="card-header">Contact Info</h2>
                </div>
                <div class="card-content-container topPadding">
                    <div class="card-content-wrap">
                        <!-- Using v-if here because we don't want the rest to register until user data is up -->
                        <ClipLoader :color="'#82BEF2'" :loading="loading" v-if="loading"></ClipLoader>
                        <form action="#" method="POST" class="form" id="user_form" v-else>
                            <div class="formgroups">
                                <div class="formgroup">
                                    <div class="input__container input-wrap">
                                        <label class="input__label" for="first_name">First Name</label>
                                        <input class="form-input form-input_text input-styles" v-model="user_editing.attributes.first_name" type="text" name="first_name"/>
                                    </div>
                                    <div class="input__container">
                                        <label class="input__label" for="last_name">Last Name</label>
                                        <input class="form-input form-input_text input-styles" v-model="user_editing.attributes.last_name" type="text" name="last_name"/>
                                    </div>
                                    <div class="input__container">
                                        <label class="input__label" for="email">Email</label>
                                        <input class="form-input form-input_text input-styles" v-validate="'required|email'" v-model="user_editing.attributes.email" type="text" name="email"/>
                                        <span v-show="errors.has('email')" class="error-text">{{ errors.first('email') }}</span>
                                    </div>
                                    <div class="input__container">
                                        <label class="input__label" for="phone">Phone Number</label>
                                        <input class="form-input form-input_text input-styles" v-model="user_editing.attributes.phone" type="number" name="phone"/>
                                    </div>
                                    <div class="input__container">
                                        <label class="input__label" for="timezone">Timezone</label>
                                        <span class="custom-select">
                                            <select name="timezone" v-model="user_editing.attributes.timezone">
                                                <option v-for="timezone in timezones">{{ timezone }}</option>
                                            </select>
                                        </span>
                                    </div>
                                    <div class="input__container">
                                        <label  class="input__label" for="gender">Gender</label>
                                        <div class="gender-options">
                                            <div class="gender-options__option">
                                                <input type="radio" name="gender" id="male" v-model="user_editing.attributes.gender" v-bind:value="'male'">
                                                <label for="male">Male</label>
                                            </div>
                                            <div class="gender-options__option">
                                                <input type="radio" name="gender" id="female" v-model="user_editing.attributes.gender" v-bind:value="'female'">
                                                <label for="female">Female</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="formgroup">
                                    <div class="profile-img-container">
                                        <ImageUpload
                                                class="profile-img-container__button"
                                                v-on:uploading="uploadingProfileImage"
                                                v-on:uploaded="uploadedProfileImage"
                                                v-on:uploadError="uploadError"
                                                label="Picture"
                                                :route="`api/v1/users/${this.user_editing.id}/image/`"
                                                type="profile">
                                        </ImageUpload>
                                        <div v-show="!loadingProfileImage" class="profile-img-container__img">
                                            <img :src="this.user_editing.attributes.image_url" />
                                        </div>
                                        <ClipLoader class="profile-img-container__img" :color="'#82BEF2'" :loading="loadingProfileImage"></ClipLoader>
                                    </div>
                                    <p class="warning">Image must be square and max 300px.</p>
                                    <div class="input__container">
                                        <label class="input__label" for="address_1">Mailing Address</label>
                                        <input class="form-input form-input_text input-styles" v-model="user_editing.attributes.address_1" type="text" name="address_1"/>
                                    </div>
                                    <div class="input__container">
                                        <label class="input__label" for="address_2">Apt/Unit #</label>
                                        <input class="form-input form-input_text input-styles" v-model="user_editing.attributes.address_2" type="text" name="address_2"/>
                                    </div>
                                    <div class="input__container">
                                        <label class="input__label" for="city">City</label>
                                        <input class="form-input form-input_text input-styles" v-model="user_editing.attributes.city" type="text" name="city"/>
                                    </div>
                                    <div class="input__container">
                                        <label class="input__label" for="state">State</label>
                                        <span class="custom-select">
                                            <select name="state" v-model="user_editing.attributes.state">
                                                <option v-for="(state, abbreviation) in states" v-bind:value="abbreviation">{{ state }}</option>
                                            </select>
                                        </span>
                                    </div>
                                    <div class="input__container">
                                        <label class="input__label" for="zip">Zip Code</label>
                                        <input class="form-input form-input_text input-styles" v-model="user_editing.attributes.zip" type="text" name="zip"/>
                                    </div>
                                </div>
                            </div>
                            <div class="error-text">
                                <p v-for="error in errorMessages">{{ error.detail }} </p>
                            </div>
                            <div class="submit inline-centered">
                                <button class="button" v-on:click.prevent="submit" :disabled="submitting">Save Changes</button><br/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{ _user_editing }}
    </div>
</template>

<script>
    import diff from 'object-diff';
    import _ from 'lodash';
    import timezones from '../../../../../../public/timezones.json';
    import states from '../../../../../../public/states.json';
    import NotificationPopup from '../../../commons/NotificationPopup.vue';
    import ImageUpload from '../../../commons/ImageUpload.vue';
    import { ClipLoader } from 'vue-spinner/dist/vue-spinner.min.js'
    import VueRouter from 'vue-router';

    export default {
        name: 'admin-profile',
        components: {
            NotificationPopup,
            ImageUpload,
            ClipLoader,
        },
        data() {
            return {
                loadingProfileImage: false, // loading of the image on image upload
                previousProfileImage: '',
                user_editing: {
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
                states: states,
                notificationSymbol: '&#10003;',
                notificationMessage: 'Changes Saved',
                notificationActive: false,
                notificationDirection: 'top-right',
                errorMessages: null,
                submitting: false,
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

                this.submitting = true;
                axios.patch(`${this.$root.$data.apiUrl}/users/${this.$route.params.id}`, this.updates)
                    .then(response => {
                        this.$root.$data.global.user_editing = response.data.data;
                        this.flashSuccess();
                        this.submitting = false;
                    })
                    .catch(err => {
                        this.errorMessages = err.response.data.errors;
                        this.submitting = false;
                    });
            },
            uploadingProfileImage() {
                this.previousProfileImage = this.user_editing.attributes.image_url;
                this.loadingProfileImage = true;
            },
            uploadedProfileImage(response) {
                this.user_editing.attributes.image_url = response.data.attributes.image_url;
                this.loadingProfileImage = false;
                this.flashSuccess();
            },
            uploadError(err) {
                this.user_editing.attributes.image_url = this.previousProfileImage;
                this.loadingProfileImage = false;
                this.errorMessages = err.errors;
            },
            getData(userId) {
                axios.get(`${this.$root.$data.apiUrl}/users/${userId}?include=patient,practitioner`)
                    .then(response => {
                        this.$root.$data.global.user_editing = response.data.data;
                        return this.$root.$data.global.loadingUserEditing = false;
                    })
                    .catch(function (error) {
                        new VueRouter().push('/') ;
                });

            }
        },
        beforeMount() {
            if ('admin' != window.Laravel.user.user_type) {
                new VueRouter().push('/') ;
            }
            this.getData(this.$route.params.id);
        },
        mounted() {
            this.$root.$data.global.currentPage = 'admin-profile';
        },
        beforeRouteUpdate (to, from, next) {
            this.$root.$data.global.loadingUserEditing = true;
            this.getData(to.params.id);
            next();
        },
        computed: {
            // loading is connected to global state since that's where the main user api call is made
            loading() {
                return this.$root.$data.global.loadingUserEditing;
            },
            // This computed property is used solely to populate this.user once the api call
            // from app.js is finished running. Sort of like a watch for parent components
            _user_editing() {
                if (!this.$root.$data.global.loadingUserEditing) {
                    this.user_editing = _.cloneDeep(this.$root.$data.global.user_editing);
                }
                return '';
            },
            updates() {
                return _.omit(diff(this.$root.$data.global.user_editing.attributes, this.user_editing.attributes), 'created_at', 'email_verified_at', 'phone_verified_at', 'doctor_name', 'image_url');
            }
        }
    }
</script>

<style lang="scss">

    .card-info {
        width: 870px;
    }

    .input__container {
        width: 80%;
    }

    .formgroups {
        display: flex;
    }

    .input-styles {
        color: #777777;
        border-bottom: 1px solid #ccc;
    }

    .form-input_text {
      padding: 0 0 5px;
      border-bottom: 1px solid #ddd;
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

    .topPadding {
        padding: 20px;
    }

    .gender-options {
        display: flex;
        width: 100%;

        input {
            margin: 0 10px 10px 0;
            max-width: 18px;
            float: left;
        }

        label {
            max-width: 80px;
            float: left;
        }

        &__option {
            width: 38%;
        }
    }

    .profile-img-container {
        display: flex;
        height: 100px;
        justify-content: space-between;
        padding-bottom: 20px;

        &__wrapper {
            width: 100%;
        }

        &__button {
            flex: 1;
        }

        &__img {
            flex: 1;
            img {
                width: 80px;
                border-radius: 50%;
                margin-top: -7px;
            }
        }
        .button {
            padding: 10px 17px;
            font-size: 13px;
            background: #DDD;
            color: #444;
            border: 1px solid #CCC;
            margin-top: 5px;
            width: 130px;
        }
    }

    .warning {
        font-size: 12px;
        margin: -20px 0 20px;
        color: #DDD;
        font-style: italic;
    }

    .loading {
        margin-left: 20px;
        color: #AAA;
    }

    .error-text {
        width: 100%;
        text-align: center;
    }

    .v-spinner {
        align-self: center;
        text-align: left !important;
    }
</style>
