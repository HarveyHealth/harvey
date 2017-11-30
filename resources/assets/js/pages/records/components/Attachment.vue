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
            <div class="card-heading-container records-spacing fullWidth floatLeft">
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
                <ClipLoader :color="'#82BEF2'" :loading="loading" v-if="loading"></ClipLoader>
            </div>
            <div class="fullWidth floatLeft quick-notes-border topMargin30">
                <h2 class="text-center">Quick Notes</h2>
                <quill-editor
                    output="html"
                    :options="editorOption"
                />
            </div>
        </div>
        <div class="record-image" v-if="!$parent.news">
            <iframe :class="{width70: $root.$data.permissions !== 'patient', floatLeft: $root.$data.permissions !== 'patient'}" :style="{height: $root.$data.permissions === 'patient' ? '80vh' : '70vh'}" class="iframe-image" :src="attachmentUrl" />
            <div v-if="$root.$data.permissions !== 'patient'" class="width30 floatLeft">
                <h2 class="text-center">Quick Notes</h2>
                <quill-editor
                    output="html"
                    :options="editorOption"
                />
            </div>
            <div v-if="$root.$data.permissions !== 'patient'" class="inline-centered fullWidth floatLeft">
                <button @click="deleteModal()" class="button bg-danger margin15">Archive Attachment</button>
            </div>
            <Modal
                :active="deleteModalActive"
                :onClose="modalClose"
                class="modal-wrapper"
            >
                <div class="card-content-wrap">
                    <div class="inline-centered">
                        <h1 class="header-xlarge">
                            <span class="text">Archive Attachment</span>
                        </h1>
                        <p>Are you sure you want to archive this attachment?</p>
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
import ClipLoader from 'vue-spinner/src/ClipLoader.vue';
import {capitalize} from 'lodash';
import moment from 'moment';
import Modal from '../../../commons/Modal.vue';
import editorOption from '../util/quillEditorObject';
import axios from 'axios';
export default {
    props: {
        patient: Object
    },
    components: {
        Modal,
        ClipLoader
    },
    directives: {
        mask
    },
    data() {
        return {
            fileName: '',
            deleteModalActive: false,
            loading: false,
            editorOption: editorOption
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
                    delete this.$parent.attachments[this.$parent.propData.id];
                    this.$parent.timeline.splice(this.$parent.index, 1);
                    this.$parent.index = null;
                    this.$parent.propData = null;
                    this.$parent.notificationMessage = "Successfully deleted!";
                    this.$parent.notificationActive = true;
                    setTimeout(() => this.$parent.notificationActive = false, 3000);
                });
        },
        upload(file) {
            let formData = new FormData();
            this.loading = true;
            formData.append('file', file.target.files[0]);
            formData.append('name', this.fileName);
            axios.post(`${this.$root.$data.apiUrl}/patients/${this.$props.patient.id}/attachments`, formData)
            .then((response) => {
                let object = {};
                let returns = response.data.data;
                this.$parent.attachments[returns.id] = returns;
                object.data = returns;
                object.id = returns.id;
                object.date = moment(returns.attributes.created_at.date).format('dddd, MMM Do YYYY');
                object.original_date = returns.attributes.created_at.date;
                object.doctor = returns.attributes.doctor_name || "No Doctor";
                object.type = returns.type.split('_').map(e => capitalize(e)).join(' ');
                this.$parent.timeline = [object].concat(this.$parent.timeline);
                this.loading = false;
                this.$parent.news = false;
                this.$parent.setIndex(0);
                this.$parent.propData = returns;
                this.$parent.notificationMessage = "Successfully added!";
                this.$parent.notificationActive = true;
                setTimeout(() => this.$parent.notificationActive = false, 3000);
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
