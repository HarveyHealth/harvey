<template>
    <div>
        <div v-if="$parent.news" class="lab-body">
            <div class="p-spacing">
                <p>
                    You are about upload a prescription for client {{ patient.search_name }}, 
                    with adate of birth {{ patient.date_of_birth }}. 
                    Please verify the name of the pharmacy before 
                    uploading, so we can keep things organized.
                    Anything you upload will be viewable to your patient.
                    The only file format accepted is a PDF.
                </p>
            </div>
            <div class="card-heading-container records-spacing">
                <div class="width-175">
                    <label class="input__label">pharmacy</label>
                    <span class="custom-select bg-white">
                        <select @change="updateName($event)">
                            <option v-for="script in prescriptionList" :data-id="script">{{ script.name }}</option>
                        </select>
                    </span>
                </div>
                <div class="width-175">
                    <label class="input__label">upload</label>
                    <label for="file-select-prescription" :class="{'disabled--cursor': !selected}">
                        <div class="border-upload-container">
                            <div class="upload-container">
                                <i class="fa fa-book pdf-icons"></i>
                                <p class="pdf-upload-text">Prescription (PDF)</p>
                            </div>
                        </div>
                    </label>
                    <input :class="{'disabled--cursor': !selected}" :disabled="!selected" @change="upload" type="file" id="file-select-prescription" accept=".pdf" hidden />
                </div>
            </div>
        </div>
        <div class="record-image" v-if="!$parent.news">
            <iframe class="iframe-image" :src="prescriptionUrl" />
            <div class="inline-centered">
                <button @click="deleteModal()" class="button bg-danger margin15">Delete Prescription</button>
            </div>
            <Modal
                :active="deleteModalActive"
                :onClose="modalClose"
                class="modal-wrapper"
            >
                <div class="card-content-wrap">
                    <div class="inline-centered">
                        <h1 class="header-xlarge">
                            <span class="text">Delete Prescription</span>
                        </h1>
                        <p>Are you sure you want to delete this prescription?</p>
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
import axios from 'axios';
import Modal from '../../../commons/Modal.vue';
export default {
    props: {
        patient: Object
    },
    components: {
        Modal
    },
    data() {
        return {
            selected: null,
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
            axios.delete(`${this.$root.$data.apiUrl}/prescriptions/${this.$parent.propData.id}`)
                .then(() => {
                    this.deleteModalActive = false;
                    this.$parent.page = 0;
                    this.$parent.timeline.splice(this.$parent.index, 1);
                    this.$parent.index = null;
                    this.$parent.notificationMessage = "Successfully deleted!";
                    this.$parent.notificationActive = true;
                    setTimeout(() => this.$parent.notificationActive = false, 3000);
                });
        },
        upload(file) {
            let formData = new FormData();
            formData.append('file', file.target.files[0]);
            formData.append('name', this.selected);
            axios.post(`${this.$root.$data.apiUrl}/patients/${this.$props.patient.id}/prescriptions`, formData)
            .then((response) => {
                console.log(`RESPONSE`, response);
            });
        },
        updateName(e) {
            this.selected = e.target.children[e.target.selectedIndex].dataset.id;
        }
    },
    computed: {
        prescriptionList() {
            return [{name: ''}].concat([{name: 'Fullscript'}]);
        },
        prescriptionUrl() {
            const prop = this.$parent.propData;
            return prop && prop.attributes && prop.attributes.url ? prop.attributes.url : '';
        }
    },
    watch : {
        prescriptionUrl(val) {
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