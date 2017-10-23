<template>
    <div class="card card-info">
        <div class="card-heading-container">
            <h2 class="heading-2">Practitioner Profile</h2>
        </div>
        <div class="card-content-container topPadding">
            <div class="card-content-wrap">
                <ClipLoader :color="'#82BEF2'" :loading="loading" v-if="loading"></ClipLoader>
                <form action="#" method="POST" class="form" id="practitioner_form" v-else>
                    <p class="practitioner-intro">Your profile information below is visible to all clients on the website. Please use proper syntax, check for spelling mistakes, and use the recommended images sizes to maximize performance of your page. To make any changes to your schedule avalability, please email <a href="mailto:support@goharvey.com">support@goharvey.com</a>.</p>
                    <div class="formgroups">
                        <div class="formgroup">
                            <div class="input__container input-wrap">
                                <label class="input__label" for="school">School</label>
                                <input class="form-input form-input_text " maxlength="50" v-model="practitioner.school" type="text" name="school"/>
                            </div>
                            <div class="input__container input-wrap">
                                <label class="input__label" for="graduated_year">Graduation Year</label>
                                <input class="form-input form-input_text " v-model="practitioner.graduated_year" type="number" name="graduated_year" maxlength="4"/>
                            </div>
                            <div class="input__container input-wrap">
                                <label class="input__label" for="license_number">License #</label>
                                <input class="form-input form-input_text " v-model="practitioner.licenses[0].number" type="text" name="licenses[]" max="10"/>
                            </div>
                            <div class="input__container input-wrap">
                                <label class="input__label" for="license_title">License Type</label>
                                <span class="custom-select">
                                    <select v-model="practitioner.licenses[0].title">
                                        <option value=""></option>
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
                                <label class="input__label">Specialties <span>(Enter 5)</span></label>
                                <input class="form-input form-input_text" type="text" name="specialty[]" v-model="practitioner.specialty[0]"/>
                                <input class="form-input form-input_text" type="text" name="specialty[]" v-model="practitioner.specialty[1]"/>
                                <input class="form-input form-input_text" type="text" name="specialty[]" v-model="practitioner.specialty[2]"/>
                                <input class="form-input form-input_text" type="text" name="specialty[]" v-model="practitioner.specialty[3]"/>
                                <input class="form-input form-input_text" type="text" name="specialty[]" v-model="practitioner.specialty[4]"/>

                            </div>
                        </div>
                        <div class="formgroup right">
                            <div class="input__container input-wrap">
                                <label class="input__label">Pictures</label>
                                <div class="practitioner-profile-images">
                                    <ClipLoader class="bg-loader" :color="'#82BEF2'" :loading="uploading_bg_image"></ClipLoader>
                                    <div v-if="!practitioner.background_picture_url || uploading_bg_image" class="practitioner-profile-images__background"></div>
                                    <img v-if="practitioner.background_picture_url && !uploading_bg_image" class="practitioner-profile-images__background" :src="practitioner.background_picture_url" />
                                    <img v-if="practitioner.picture_url" class="practitioner-profile-images__profile" :src="practitioner.picture_url" />
                                    <img v-else class="practitioner-profile-images__profile" src="https://d35oe889gdmcln.cloudfront.net/assets/images/default_user_image.png" />
                                    <ClipLoader :color="'#82BEF2'" :loading="uploading_profile_image"></ClipLoader>
                                </div>
                            </div>
                            <div class="profile-title">
                                <h4 class="heading-3-expand">Dr. {{ practitioner.name }}, ND</h4><br>
                            </div>
                            <div class="image-upload-buttons">
                                <ImageUpload
                                    class="upload-button"
                                    v-on:uploading="uploadingProfileImage"
                                    v-on:uploaded="uploadedProfileImage"
                                    label="Headshot"
                                    :route="`api/v1/practitioners/${practitioner_id}/profile-image/`"
                                    type="practitioner-profile">
                                </ImageUpload>
                                <ImageUpload
                                    class="upload-button"
                                    v-on:uploading="uploadingBackgroundImage"
                                    v-on:uploaded="uploadedBackgroundImage"
                                    label="Background"
                                    :route="`api/v1/practitioners/${practitioner_id}/bg-image/`"
                                    type="header">
                                </ImageUpload>
                            </div>
                            <p class="copy-muted-2 font-italic font-sm font-thin" style="margin:12px 0 34px;">Recommended image dimensions are 300x300 for the thumbnail and 400x100 for the background.</p>
                            <div class="input__container input-wrap font-sm">
                                <label class="input__label" for="description">Description</label>
                                <textarea
                                  v-model="practitioner.description"
                                  maxlength="300" name="description"
                                  id="description" class="input--textarea"
                                  placeholder="Enter a brief description."
                                  style="min-height: 150px;"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="error-text">
                        <p v-for="error in errorMessages">{{ error.detail }} </p>
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
    import { ClipLoader } from 'vue-spinner/dist/vue-spinner.min.js';

    export default {
        name: 'practitioner-profile',
        data() {
            return {
                practitioner_id: Laravel.user.practitionerId || this.practitionerIdEditing,
                practitioner: {
                    licenses: [{'number': '', 'state': '', 'title': ''}],
                    picture_url : 'https://d35oe889gdmcln.cloudfront.net/assets/images/default_user_image.png',
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
                submitting: false
            };
        },
        components: {
            ImageUpload,
            ClipLoader
        },
        methods: {
            submit() {
                this.submitting = true;
                this.resetErrors();
                const payload =  _.omit(this.practitioner, 'type_name', 'background_picture_url', 'picture_url',);
                axios.patch(`/api/v1/practitioners/${this.practitioner_id}`, payload)
                    .then(response => {
                        this.practitioner = response.data.data.attributes;
                        this.practitioner.licenses[0] = this.practitioner.licenses[0] || {'number': '', 'state': '', 'title': ''};
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
            }
        },
        computed: {
          loading() {
            return this.$root.$data.global.practitionerProfileLoading;
          }
        },
        mounted() {
            axios.get(`/api/v1/practitioners/${this.practitioner_id}`)
                .then(response => {
                    this.practitioner = response.data.data.attributes;
                    this.practitioner.licenses[0] = this.practitioner.licenses[0] || {'number': '', 'state': '', 'title': ''};
                    this.$root.$data.global.practitionerProfileLoading = false;
                })
                .catch(() => this.practitioner = {});
        },
        props: {
            flashSuccess: {
                type: Function
            },
            practitionerIdEditing: {
                type: String,
                default: null
            }
        }
    };
</script>

<style lang="scss">

    input, label {
        display: block;
        width: 80%;
    }

    .input {
        &__label{
            span {
                color: #eee;
            }
        }
    }

    #header,
    #practitioner-profile{
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

    .practitioner-intro {
        font-size: 15px;
        padding: 0 0 20px 20px;
    }



    .formgroups {
        display: flex;
    }

    .formgroup {
        flex: 1;
        &.right {
            .input__container {
                width: 90%;
            }
        }
    }

    .submit {
        padding-top: 20px;
        display: flex;
        justify-content: center;
    }

    .image-upload-buttons {
        display: flex;
        justify-content: space-around;
        .upload-button {
            width: 50%;
        }
    }

    .practitioner-profile-images {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 90%;
        margin: 15px 0 -40px 17px;

        &__profile {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: -60px auto 10px;
            display: flex;
            z-index: 3;
        }

        &__background {
            width: 315px;
            background-color: #FBFBFB;
            z-index: 1;
        }
    }

    .input__container {
        em {
            color: #777;
            font-size: 14px;
            line-height: 18px;
        }
    }

    .profile-title {
        display: flex;
        justify-content: center;
        font-size: 1.5em;
        padding: 1em 0;
    }

    .warning.prac {
        margin-top: -7px;
    }

    .bg-loader {
        position: relative;
        top: 80px;
        z-index: 2;
    }
</style>
