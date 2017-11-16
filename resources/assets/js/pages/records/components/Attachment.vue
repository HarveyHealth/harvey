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
            <iframe class="iframe-image" :src="attachmentUrl" />
            <div class="inline-centered">
                <button @click="deleteModal()" class="button bg-danger margin15">Delete Note</button>
            </div>
            <Modal
                :active="deleteModalActive"
                :onClose="modalClose"
                class="modal-wrapper"
            >
                <div class="card-content-wrap">
                    <div class="inline-centered">
                        <h1 class="header-xlarge">
                            <span class="text">Delete Attachment</span>
                        </h1>
                        <p>Are you sure you want to delete this attachment?</p>
                        <div class="button-wrapper">
                            <button class="button button--cancel" @click="modalClose">Cancel</button>
                            <button class="button" @click="deleteItem">Yes, Confirm</button>
                        </div>
                    </div>
                </div>
            </Modal>
        </div>
    </div>
</template>

<script>
import {mask} from 'vue-the-mask';
import {capitalize} from 'lodash';
import Modal from '../../../commons/Modal.vue';
import axios from 'axios';
export default {
    props: {
        patient: Object
    },
    components: {
        Modal
    },
    directives: {
        mask
    },
    data() {
        return {
            fileName: '',
            deleteModalActive: false
        };
    },
    methods: {
        deleteModal() {
            this.deleteModalActive = true;
        },
        modalClose() {
            this.deleteModalActive = false;
        },
        deleteItem() {
            axios.delete(`${this.$root.$data.apiUrl}/attachments/${this.$parent.propData.id}`)
                .then(() => {
                    this.deleteModalActive = false;
                    this.$parent.page = 0;
                    this.$parent.index = null;
                    this.$parent.timeline = [];
                    this.$parent.loading = true;
                    this.$parent.getTimelineData();
                    this.$parent.notificationMessage = "Successfully deleted!";
                    this.$parent.notificationActive = true;
                    setTimeout(() => this.$parent.notificationActive = false, 3000);
                });
        },
        upload(file) {
            let formData = new FormData();
            formData.append('file', file.target.files[0]);
            formData.append('name', this.fileName);
            axios.post(`${this.$root.$data.apiUrl}/patients/${this.$props.patient.id}/attachments`, formData)
            .then((response) => {
                console.log(`RESPONSE`, response);
            });
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
