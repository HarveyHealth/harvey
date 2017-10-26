<template>
    <div class="lab-body">
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
                    <select>
                        <option v-for="lab in labNameList">{{ lab.name }}</option>
                    </select>
                </span>
            </div>
            <div class="width-175">
                <label class="input__label">lab test</label>
                <span class="custom-select bg-white">
                    <select>
                        <option v-for="lab in labTestList">{{ lab.attributes.name }}</option>
                    </select>
                </span>
            </div>
            <div class="width-175">
                <label class="input__label">upload</label>
                <div class="border-upload-container">
                    <div class="upload-container">
                        <i class="fa fa-book pdf-icons"></i>
                        <p class="pdf-upload-text">Lab Result (PDF)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        patient: Object
    },
    data() {
        return {

        };
    },
    computed: {
        labNameList() {
            let labNames = Object.keys(this.$root.$data.labTypes);
            return [{name: ''}].concat(labNames);
        },
        labTestList() {
            let labTests = Object.values(this.$root.$data.labTests);
            return [{attributes: {name: ''}}].concat(labTests);
        }
    }
};
</script>
