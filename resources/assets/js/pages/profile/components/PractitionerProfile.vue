<template>
    <div class="card card-info">
        <div class="card-heading-container">
            <h2 class="card-header">Practitioner Info (Public)</h2>
        </div>
        <div class="card-content-container">
            <div class="card-content-wrap">
                <form action="#" method="POST" class="form" id="practitioner_form">
                    <div class="formgroups">
                        <div class="formgroup">
                            <div class="input__container">
                                <label for="school">School</label>
                                <input maxlength="50" v-model="practitioner.school" type="text" name="school"/>
                            </div>
                            <div class="input__container">
                                <label for="graduated_year">Graduation</label>
                                <input v-model="practitioner.graduated_year" type="text" name="graduated_year"/>
                            </div>
                            <div class="input__container">
                                <label for="license_number">License #</label>
                                <input v-model="practitioner.license_number" type="text" name="license_number" max="10"/>
                            </div>
                            <div class="input__container">
                                <label for="license_title">License Type</label>
                                <select disabled>
                                    <option v-for="license_type in license_types" name="license_title" v-model="practitioner.license_title">
                                        {{ license_names[license_type] }} ({{ license_type }})
                                    </option>
                                </select>
                            </div>
                            <div class="input__container">
                                <label for="license_state">License State</label>
                                <select v-model="practitioner.license_state" name="license_state">
                                    <option value="Alabama">Alabama</option>
                                    <option value="Alaska">Alaska</option>
                                    <option value="Arizona">Arizona</option>
                                    <option value="Arkansas">Arkansas</option>
                                    <option value="California">California</option>
                                    <option value="Colorado">Colorado</option>
                                    <option value="Connecticut">Connecticut</option>
                                    <option value="Delaware">Delaware</option>
                                    <option value="District Of Columbia">District Of Columbia</option>
                                    <option value="Florida">Florida</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Hawaii">Hawaii</option>
                                    <option value="Idaho">Idaho</option>
                                    <option value="Illinois">Illinois</option>
                                    <option value="Indiana">Indiana</option>
                                    <option value="Iowa">Iowa</option>
                                    <option value="Kansas">Kansas</option>
                                    <option value="Kentucky">Kentucky</option>
                                    <option value="Louisiana">Louisiana</option>
                                    <option value="Maine">Maine</option>
                                    <option value="Maryland">Maryland</option>
                                    <option value="Massachusetts">Massachusetts</option>
                                    <option value="Michigan">Michigan</option>
                                    <option value="Minnesota">Minnesota</option>
                                    <option value="Mississippi">Mississippi</option>
                                    <option value="Missouri">Missouri</option>
                                    <option value="Montana">Montana</option>
                                    <option value="Nebraska">Nebraska</option>
                                    <option value="Nevada">Nevada</option>
                                    <option value="New Hampshire">New Hampshire</option>
                                    <option value="New Jersey">New Jersey</option>
                                    <option value="New Mexico">New Mexico</option>
                                    <option value="New York">New York</option>
                                    <option value="North Carolina">North Carolina</option>
                                    <option value="North Dakota">North Dakota</option>
                                    <option value="Ohio">Ohio</option>
                                    <option value="Oklahoma">Oklahoma</option>
                                    <option value="Oregon">Oregon</option>
                                    <option value="Pennsylvania">Pennsylvania</option>
                                    <option value="Rhode Island">Rhode Island</option>
                                    <option value="South Carolina">South Carolina</option>
                                    <option value="South Dakota">South Dakota</option>
                                    <option value="Tennessee">Tennessee</option>
                                    <option value="Texas">Texas</option>
                                    <option value="Utah">Utah</option>
                                    <option value="Vermont">Vermont</option>
                                    <option value="Virginia">Virginia</option>
                                    <option value="Washington">Washington</option>
                                    <option value="West Virginia">West Virginia</option>
                                    <option value="Wisconsin">Wisconsin</option>
                                    <option value="Wyoming">Wyoming</option>
                                </select>
                            </div>
                            <div class="input__container">
                                <label>Specialties</label>
                                <input v-model="practitioner.specialty_1" type="text"/>
                                <input v-model="practitioner.specialty_2" type="text"/>
                                <input v-model="practitioner.specialty_3" type="text"/>
                                <input v-model="practitioner.specialty_4" type="text"/>
                                <input v-model="practitioner.specialty_5" type="text"/>
                            </div>
                        </div>
                        <div class="formgroup">
                            <div class="profile-images">
                                <div v-if="!this.practitioner_background_image" class="profile-images__background"></div>
                                <img v-if="this.practitioner_background_image" class="profile-images__background" :src="this.practitioner_background_image" />
                                <img class="profile-images__profile" :src="this.practitioner_profile_image" />
                            </div>
                            <div class="image-upload-buttons">
                                <ImageUpload
                                        v-on:uploading="uploadingProfileImage"
                                        v-on:uploaded="uploadedProfileImage"
                                        label="Headshot"
                                        :route="`api/v1/practitioners/${this.practitioner_id}/profile-image/`"
                                        type="profile">
                                </ImageUpload>

                                <ImageUpload
                                        v-on:uploading="uploadingBackgroundImage"
                                        v-on:uploaded="uploadedBackgroundImage"
                                        label="Background"
                                        :route="`api/v1/practitioners/${this.practitioner_id}/bg-image/`"
                                        type="header">
                                </ImageUpload>
                            </div>
                            <div class="input__container">
                                <label for="description">Description</label>
                                <textarea maxlength="300" name="description" id="description">{{ practitioner.description }}</textarea>
                            </div>
                            <div class="input__container">
                                <label for="rate">Hourly Rate</label>
                                <input v-model="practitioner.rate" type="text" name="rate" disabled/>
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
</template>

<script>
    import _ from 'lodash';
    import ImageUpload from '../../../commons/ImageUpload.vue';
    import LicenseTypes from '../../../../../../public/licensetypes.json';



    export default {
        name: 'practitioner-profile',
        data() {
            return {
                practitioner_id: Laravel.user.practitionerId,
                practitioner: {},
                license_types: Object.keys(LicenseTypes),
                license_names: LicenseTypes,
                previousProfileImage: null,
                previousBackgroundImage: null,
            }
        },
        components: {
            ImageUpload,
        },
        methods: {
            submit() {
                axios.patch(`/api/v1/practitioners/${this.practitioner_id}`, this.practitioner)
                    .then(response => {
                        this.practitioner = response.data.data.attributes;
                        this.flashSuccess();
                    });
            },
            uploadingProfileImage() {
              this.previousProfileImage = this.practitioner.picture_url;
              this.practitioner.picture_url = '/images/loading.gif';
            },
            uploadedProfileImage(response) {
                if(response) {
                    const updatedImage = JSON.parse(response).data.attributes.picture_url;
                    this.practitioner.picture_url = updatedImage;
                    this.flashSuccess();
                } else {
                    this.practitioner.picture_url = this.previousProfileImage;
                }
            },
            uploadingBackgroundImage() {
                this.previousBackgroundImage = this.practitioner.background_picture_url;
                this.practitioner.background_picture_url = '';
            },
            uploadedBackgroundImage(response) {
                if(response) {
                    this.practitioner.background_picture_url = JSON.parse(response).data.attributes.background_picture_url;
                    this.flashSuccess();
                } else {
                    this.practitioner.background_picture_url = this.previousBackgroundImage;
                }
            },
        },
        created() {
            axios.get(`/api/v1/practitioners/${Laravel.user.practitionerId}`)
                .then(response => {
                    this.practitioner = response.data.data.attributes;
                })
                .catch(error => this.practitioner = {});
        },
        computed: {
            practitioner_profile_image() {
                return this.practitioner.picture_url ? this.practitioner.picture_url : '/images/default_user_image.png';
            },
            practitioner_background_image() {
                return this.practitioner.background_picture_url ? this.practitioner.background_picture_url : '';
            }
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

    .profile-images {
        display: flex;
        flex-direction: column;
        align-items: center;

        &__profile {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-top: -50px;
        }

        &__background {
            width: 400px;
            height: 100px;
            background-color: #FBFBFB;
        }
    }
</style>
