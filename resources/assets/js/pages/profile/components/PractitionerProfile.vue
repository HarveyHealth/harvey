<template>
    <div class="card card-info">
        <div class="card-heading-container">
            <h2 class="card-header">Practitioner Info (Public)</h2>
        </div>
        <div class="card-content-container">
            <div class="card-content-wrap">
                <ClipLoader :color="'#82BEF2'" :loading="loading"></ClipLoader>

                <div class="error-text">
                    <p v-for="error in errorMessages">{{ error.detail }} </p>
                </div>

                <form action="#" method="POST" class="form" id="practitioner_form" v-show="!loading">
                    <div class="formgroups">
                        <div class="formgroup">
                            <div class="input__container input-wrap">
                                <label class="input__label" for="school">School</label>
                                <input class="form-input form-input_text font-darkest-gray" maxlength="50" v-model="practitioner.school" type="text" name="school"/>
                            </div>
                            <div class="input__container input-wrap">
                                <label class="input__label" for="graduated_year">Graduation Year</label>
                                <input class="form-input form-input_text font-darkest-gray" v-model="practitioner.graduated_year" type="number" name="graduated_year" maxlength="4"/>
                            </div>
                            <div class="input__container input-wrap">
                                <label class="input__label" for="license_number">License #</label>
                                <input class="form-input form-input_text font-darkest-gray" v-model="practitioner.licenses[0].number" type="text" name="licenses[0]" max="10" v-validate="{ max: 10, regex: /^[a-zA-Z]{2,3}-\d{3,6}$/ }"/>
                                <span v-show="errors.has('licenses[0]')" class="error-text">Invalid license format.</span>
                            </div>
                            <div class="input__container input-wrap">
                                <label class="input__label" for="license_title">License Type</label>
                                <span class="custom-select isdisabled">
                                    <select v-model="practitioner.licenses[0].number.toUpperCase().split('-')[0]" disabled>
                                        <option v-for="license_type in license_types" name="license_title" v-bind:value="license_type">
                                            {{ license_names[license_type] }} ({{ license_type }})
                                        </option>
                                    </select>
                                </span>
                            </div>
                            <div class="input__container input-wrap">
                                <label class="input__label" for="license_state">License State</label>
                                <span class="custom-select">
                                    <select name="license_state" v-model="practitioner.licenses[0].state">
                                        <option value=""></option>
                                        <option v-for="(state, abbreviation) in states" v-bind:value="abbreviation">{{ state }}</option>
                                    </select>
                                </span>
                            </div>
                            <div class="input__container input-wrap">
                                <label class="input__label">Specialties</label>
                                <input class="form-input form-input_text font-darkest-gray" type="text" name="specialty[]" v-model="practitioner.specialty[0]"/>
                                <input class="form-input form-input_text font-darkest-gray" type="text" name="specialty[]" v-model="practitioner.specialty[1]"/>
                                <input class="form-input form-input_text font-darkest-gray" type="text" name="specialty[]" v-model="practitioner.specialty[2]"/>
                                <input class="form-input form-input_text font-darkest-gray" type="text" name="specialty[]" v-model="practitioner.specialty[3]"/>
                                <input class="form-input form-input_text font-darkest-gray" type="text" name="specialty[]" v-model="practitioner.specialty[4]"/>

                            </div>
                        </div>
                        <div class="formgroup">
                            <div class="practitioner-profile-images">
                                <ClipLoader class="bg-loader" :color="'#82BEF2'" :loading="uploading_bg_image"></ClipLoader>
                                <div v-if="!practitioner.background_picture_url || uploading_bg_image" class="practitioner-profile-images__background"></div>
                                <img v-if="practitioner.background_picture_url && !uploading_bg_image" class="practitioner-profile-images__background" :src="practitioner.background_picture_url" />
                                <img v-if="!uploading_profile_image" class="practitioner-profile-images__profile" :src="practitioner.picture_url" />
                                <ClipLoader :color="'#82BEF2'" :loading="uploading_profile_image"></ClipLoader>
                            </div>
                            <div class="profile-title">
                                <h3>Dr. {{ practitioner.name }}, N.D.</h3>
                            </div>
                            <div class="image-upload-buttons">
                                <ImageUpload
                                        v-on:uploading="uploadingProfileImage"
                                        v-on:uploaded="uploadedProfileImage"
                                        label="Headshot"
                                        :route="`api/v1/practitioners/${practitioner_id}/profile-image/`"
                                        type="practitioner-profile">
                                </ImageUpload>

                                <ImageUpload
                                        v-on:uploading="uploadingBackgroundImage"
                                        v-on:uploaded="uploadedBackgroundImage"
                                        label="Background"
                                        :route="`api/v1/practitioners/${practitioner_id}/bg-image/`"
                                        type="header">
                                </ImageUpload>
                            </div>
                            <div class="input__container input-wrap">
                                <label class="input__label" for="description">Description</label>
                                <textarea v-model="practitioner.description" maxlength="300" name="description" id="description" placeholder="Enter a brief description."></textarea>
                            </div>
                            <div class="input__container input-wrap">
                                <label class="input__label">Schedule</label>
                                <em>Please message us in Slack to make changes to your weekly availability.</em>
                            </div>
                        </div>
                    </div>
                    <div class="submit inline-centered">
                        <button class="button" v-on:click.prevent="submit" :disabled="submitting || errors.any()">Save Changes</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</template>

<script>
    import _ from 'lodash';
    import ImageUpload from '../../../commons/ImageUpload.vue';
    import LicenseTypes from '../../../../../../public/licensetypes.json';
    import states from '../../../../../../public/states.json';
    import { ClipLoader } from 'vue-spinner/dist/vue-spinner.min.js'

    export default {
        name: 'practitioner-profile',
        data() {
            return {
                loading: true,
                practitioner_id: Laravel.user.practitionerId,
                practitioner: {
                    licenses: [{'number': '', 'state': ''}],
                    picture_url : '/images/default_user_image.png',
                    background_picture_url: '',
                    specialty: []
                },
                license_types: Object.keys(LicenseTypes),
                license_names: LicenseTypes,
                previousProfileImage: null,
                previousBackgroundImage: null,
                states: states,
                uploading_bg_image: false,
                uploading_profile_image: false,
                errorMessages: null,
                submitting: false,
            }
        },
        components: {
            ImageUpload,
            ClipLoader,
        },
        methods: {
            submit() {
                this.submitting = true;
                this.resetErrors();
                const payload =  _.omit(this.practitioner, 'type_name', 'background_picture_url', 'picture_url',);
                axios.patch(`/api/v1/practitioners/${this.practitioner_id}`, payload)
                    .then(response => {
                        this.practitioner = response.data.data.attributes;
                        this.submitting = false;
                        this.flashSuccess();
                    })
                    .catch((err) => {
                        this.submitting = false;
                        this.errorMessages = err.response.data.errors;
                    });
            },
            resetErrors() {
              this.errorMessages = null;
            },
            uploadingProfileImage() {
                this.uploading_profile_image = true;
            },
            uploadedProfileImage(response) {
                this.practitioner.picture_url = response.data.attributes.picture_url;
                this.uploading_profile_image = false;
                this.flashSuccess();
            },
            uploadingBackgroundImage() {
                this.uploading_bg_image = true;
            },
            uploadedBackgroundImage(response) {
                this.practitioner.background_picture_url = response.data.attributes.background_picture_url;
                this.uploading_bg_image = false;
                this.flashSuccess();
            },
        },
        created() {
            axios.get(`/api/v1/practitioners/${Laravel.user.practitionerId}`)
                .then(response => {
                    this.practitioner = response.data.data.attributes;
                    this.practitioner.licenses[0] = this.practitioner.licenses[0] || {'number': '', 'state': ''};
                    this.loading = false;
                })
                .catch(error => this.practitioner = {});
        },
        props: {
            flashSuccess: {
                type: Function,
            },
        }
    }
</script>

<style lang="scss">
    input, label {
        display: block;
        width: 80%;
    }

    textarea#description {
        width: 100%;
        height: 200px;
    }

    .formgroups {
        display: flex;
    }

    .formgroup {
        flex: 1;
    }

    .submit {
        padding-top: 20px;
        display: flex;
        justify-content: center;
    }

    .card-info {
        width: 75%;
    }

    .image-upload-buttons {
        display: flex;
        justify-content: space-around;
    }

    .practitioner-profile-images {
        display: flex;
        flex-direction: column;
        align-items: center;

        &__profile {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-top: -65px;
            z-index: 3;
        }

        &__background {
            width: 400px;
            height: 120px;
            background-color: #FBFBFB;
            z-index: 1;
        }
    }

    .profile-title {
        display: flex;
        justify-content: center;
        font-size: 1.5em;
        padding-bottom: 20px;
    }

    .bg-loader {
        position: relative;
        top: 80px;
        z-index: 2;
    }
</style>
