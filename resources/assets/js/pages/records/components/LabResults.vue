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
            </div>
        </div>
        <div class="record-image" v-if="!$parent.news">
            <iframe class="record-image" :src="resultUrl" />
        </div>
    </div>
</template>

<script>
import axios from 'axios';
export default {
    props: {
        patient: Object
    },
    data() {
        return {
            selectedLabName: null,
            selectedLabType: null,
        };
    },
    methods: {
        updateLabType(e) {
            this.selectedLabName = e.target.children[e.target.selectedIndex].dataset.id;
        },
        updateLab(e) {
            this.selectedLabType = e.target.children[e.target.selectedIndex].dataset.id;
        },
        upload(file) {
            let formData = new FormData();
            formData.append('file', file.target.files[0]);
            axios.post(`${this.$root.$data.apiUrl}/lab/tests/${this.selectedLabType}/results`, formData)
            .then((response) => {
                console.log(`RESPONSE`, response);
            })
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