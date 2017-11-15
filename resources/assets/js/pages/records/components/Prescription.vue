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
            <iframe class="record-image" :src="prescriptionUrl" />
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
            selected: null,
        };
    },
    methods: {
        upload(file) {
            axios.post(`${this.$root.$data.apiUrl}/patients/${this.$props.patient.id}/prescriptions`, {
                file: file.target.files[0],
                name: this.selected,
            })
            .then((response) => {
                console.log(`RESPONSE`, response);
            })
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