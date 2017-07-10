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
                            <label for="school">School</label>
                            <input type="text" name="school"/>
                            <label for="graduated_at">Graduation</label>
                            <input type="text" name="graduated_at"/>
                            <label for="license_number">License #</label>
                            <input type="text" name="license_number"/>
                            <label for="license_title">License Type</label>
                            <input type="text" name="license_title"/>
                        </div>
                        <div class="formgroup">
                            <div class="image-upload-buttons">
                                <ImageUpload
                                        :model="'practitioner'"
                                        :model_id="this.practitioner_id"
                                        :type="'picture'">
                                </ImageUpload>
                                <ImageUpload
                                        :model="'practitioner'"
                                        :model_id="this.practitioner_id"
                                        :type="'background_picture'">
                                </ImageUpload>
                            </div>
                            <label for="description">Description</label>
                            <textarea name="description" id="description"></textarea>
                        </div>
                    </div>
                    <div class="submit">
                        <button v-on:click.prevent="submit" >Save Changes</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import _ from 'lodash';
    import ImageUpload from '../../../commons/ImageUpload.vue';

    export default {
        name: 'practitioner-profile',
        data() {
            return {
                practitioner_id: Laravel.user.practitionerId,
                practitioner: {}
            }
        },
        components: {
            ImageUpload,
        },
        methods: {
            submit() {
                console.log('submitted');
            },
            getPractitioner() {
                axios.get(`/api/v1/practitioners/${Laravel.user.practitionerId}`)
                    .then(response => {
                        this.practitioner = response.data.data.attributes;
                    })
                    .catch(error => this.practitioner = {});
            },
        },
        created() {
            this.getPractitioner();
        }
    }
</script>

<style>
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
</style>
