<template>
    <div>
        <div v-if="$parent.news" class="lab-body">
            <div class="p-spacing">
                <p>
                    You are about upload a new lab test for client {{ patient.search_name }} 
                    (date of birth {{ patient.date_of_birth }}). 
                    Please verify the name of the lab and the type of lab test before 
                    uploading the results, so we can match the result with a lab test. 
                    The only file format accepted is a PDF.
                </p>
            </div>
            <div class="card-heading-container lab-spacing">
                <div class="width-175">
                    <label class="input__label">lab name</label>
                    <span class="custom-select bg-white">
                        <select @change="updateLabType($event)">
                            <option v-for="lab in labNameList" :data-id="lab">{{ lab }}</option>
                        </select>
                    </span>
                </div>
                <div class="width-175">
                    <label class="input__label">lab test</label>
                    <span class="custom-select bg-white">
                        <select @change="updateLab($event)">
                            <option v-for="lab in labTestList" :data-id="lab.id">{{ lab.included.attributes.name }}</option>
                        </select>
                    </span>
                </div>
                <div class="width-175">
                    <label class="input__label">upload</label>
                    <label for="file-select-prescription" :class="{'disabled--cursor': !selectedLabName || !selectedLabType}">
                        <div class="border-upload-container">
                            <div class="upload-container">
                                <i class="fa fa-book pdf-icons"></i>
                                <p class="pdf-upload-text">Lab Result (PDF)</p>
                            </div>
                        </div>
                    </label>
                    <input :class="{'disabled--cursor': !selectedLabName || !selectedLabType}" :disabled="!selectedLabName || !selectedLabType" @change="upload" type="file" id="file-select-prescription" accept=".pdf" hidden />
                </div>
                <ClipLoader :color="'#82BEF2'" :loading="loading" v-if="loading"></ClipLoader>
            </div>
        </div>
        <div class="record-image" v-if="!$parent.news">
            <iframe class="iframe-image" :src="resultUrl" />
            <div class="inline-centered">
                <button @click="deleteModal()" class="button bg-danger margin15">Delete Result</button>
            </div>
            <Modal
                :active="deleteModalActive"
                :onClose="modalClose"
                class="modal-wrapper"
            >
                <div class="card-content-wrap">
                    <div class="inline-centered">
                        <h1 class="header-xlarge">
                            <span class="text">Delete Lab Result</span>
                        </h1>
                        <p>Are you sure you want to delete this lab result?</p>
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
import ClipLoader from 'vue-spinner/src/ClipLoader.vue';
import {capitalize} from 'lodash';
import moment from 'moment';
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
            selectedLabName: null,
            selectedLabType: null,
            deleteModalActive: false,
            loading: false
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
            axios.delete(`${this.$root.$data.apiUrl}/lab/tests/results/${this.$parent.propData.id}`)
                .then(() => {
                    this.deleteModalActive = false;
                    this.$parent.page = 0;
                    delete this.$parent.lab_test_results[this.$parent.propData.id];
                    this.$parent.timeline.splice(this.$parent.index, 1);
                    this.$parent.index = null;
                    this.$parent.propData = null;
                    this.$parent.notificationMessage = "Successfully deleted!";
                    this.$parent.notificationActive = true;
                    setTimeout(() => this.$parent.notificationActive = false, 3000);
                });
        },
        updateLabType(e) {
            this.selectedLabName = e.target.children[e.target.selectedIndex].dataset.id;
        },
        updateLab(e) {
            this.selectedLabType = e.target.children[e.target.selectedIndex].dataset.id;
        },
        upload(file) {
            this.loading = true;
            let formData = new FormData();
            formData.append('file', file.target.files[0]);
            axios.post(`${this.$root.$data.apiUrl}/lab/tests/${this.selectedLabType}/results`, formData)
            .then((response) => {
                let results = response.data.included.map(e => {
                    let object = {};
                    let returns = e;
                    this.$parent.lab_test_results[returns.id] = returns;
                    object.data = returns;
                    object.id = returns.id;
                    object.date = moment(returns.attributes.created_at.date).format('dddd, MMM Do YYYY');
                    object.original_date = returns.attributes.created_at.date;
                    object.doctor = returns.attributes.doctor_name || "No Doctor";
                    object.type = returns.type.split('_').map(e => capitalize(e)).join(' ');
                    return object;
                });
                this.$parent.timeline = results.concat(this.$parent.timeline);
                this.loading = false;
                this.$parent.news = false;
                this.$parent.setIndex(0);
                this.$parent.propData = results[0];
                this.$parent.notificationMessage = "Successfully added!";
                this.$parent.notificationActive = true;
                setTimeout(() => this.$parent.notificationActive = false, 3000);
            });
        }
    },
    computed: {
        labNameList() {
            let labNames = Object.keys(this.$root.$data.labTypes);
            return [''].concat(labNames);
        },
        labTestList() {
            let labTests = Object.values(this.$parent.lab_tests);
            return labTests.length ? [{included: {attributes: {name: ''}}, id: 0}].concat(labTests) : [{included: {attributes: {name: 'No Lab Tests'}}, id: 0}];
        },
        resultUrl() {
            const prop = this.$parent.propData;
            return prop && prop.attributes && prop.attributes.url ? prop.attributes.url : '';
        }
    },
    watch: {
        resultUrl(val) {
            if (!val) {
                const prop = this.$parent.propData;
                return prop && prop.attributes && prop.attributes.url ? prop.attributes.url : '';
            }
        },
        labNameList(val) {
            if (!val) {
                let labNames = Object.keys(this.$root.$data.labTypes);
                return [''].concat(labNames);
            }
        },
        labTestList(val) {
            if (!val) {
                let labTests = Object.values(this.$parent.lab_tests);
                return labTests.length ? [{included: {attributes: {name: ''}}, id: 0}].concat(labTests) : [{included: {attributes: {name: 'No Lab Tests'}}, id: 0}];
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