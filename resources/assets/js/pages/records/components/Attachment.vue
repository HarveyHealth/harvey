<template>
    <div>
        <div v-if="$parent.news" class="lab-body">
            <div class="p-spacing">
                <p>
                    You are about upload an attachment for client {{ patient.search_name }}, 
                    with adate of birth {{ patient.date_of_birth }}. 
                    Please verify the name of attachment before 
                    uploading, so we can keep things organized.
                    Anything you upload will be viewable to your doctor and doctor's assisstants only.
                    The only file format accepted is a PDF.
                </p>
            </div>
            <div class="card-heading-container records-spacing">
                <div class="width-175">
                    <label class="input__label">file upload</label>
                    <input class="bg-white input--text" v-model="fileName" placeholder="Enter file name">
                </div>
                <div class="width-175">
                    <label class="input__label">upload</label>
                    <label for="file-select-prescription" :class="{'disabled--cursor': fileName === ''}">
                        <div class="border-upload-container">
                            <div class="upload-container">
                                <i class="fa fa-book pdf-icons"></i>
                                <p class="pdf-upload-text">Attachment (PDF)</p>
                            </div>
                        </div>
                    </label>
                    <input :class="{'disabled--cursor': fileName === ''}" :disabled="fileName === ''" @change="upload" type="file" id="file-select-prescription" accept=".pdf" hidden />
                </div>
            </div>
        </div>
        <div class="record-image" v-if="!$parent.news">
            <iframe class="record-image" :src="attachmentUrl" />
        </div>
    </div>
</template>

<script>
import {mask} from 'vue-the-mask'
import {capitalize} from 'lodash'
import axios from 'axios';
export default {
    props: {
        patient: Object
    },
    directives: {
        mask,
    },
    data() {
        return {
            fileName: ''
        };
    },
    methods: {
        upload(file) {
            let formData = new FormData();
            formData.append('file', file.target.files[0]);
            formData.append('name', this.fileName);
            axios.post(`${this.$root.$data.apiUrl}/patients/${this.$props.patient.id}/attachments`, formData)
            .then((response) => {
                console.log(`RESPONSE`, response);
            })
        }
    },
    computed: {
        attachmentUrl() {
            const prop = this.$parent.propData;
            return prop && prop.attributes && prop.attributes.url ? prop.attributes.url : '';
        }
    },
    watch: {
        attachmentUrl(val) {
            if (!val) {
                const prop = this.$parent.propData;
                return prop && prop.attributes && prop.attributes.url ? prop.attributes.url : '';
            }
        }
    }
};
</script>

<style>
    .disabled--cursor {
        cursor: not-allowed;
    }
</style>
