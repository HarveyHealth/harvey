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
        <div class="card-heading-container records-spacing">
            <div style="width: 175px">
                <label class="input__label">lab name</label>
                <span class="custom-select bg-white">
                    <select>
                        <option v-for="lab in labNameList">{{ lab.name }}</option>
                    </select>
                </span>
            </div>
            <div style="width: 175px">
                <label class="input__label">lab test</label>
                <span class="custom-select bg-white">
                    <select>
                        <option v-for="lab in labTestList">{{ lab.attributes.name }}</option>
                    </select>
                </span>
            </div>
            <div style="width: 175px">
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

        }
    },
    computed: {
        labNameList() {
            return [{name: ''}].concat([{name: 'SpectraCell'}]);
        },
        labTestList() {
            let labTests = Object.values(this.$root.$data.labTests)
            return [{attributes: {name: ''}}].concat(labTests)
        }
    }
}
</script>

<style lang="scss">
    .records-spacing {
        display: flex;
        justify-content: space-around;
        width: 700px;
    }
    .p-spacing {
        padding: 25px;
    }
    .lab-body {
        height: auto; 
        padding: 25px; 
        overflow-x: hidden; 
        overflow-y: scroll;
        width: 100%;
    }
    .pdf-upload-text {
        margin: 0;
    }
    .upload-container {
        display: flex;
        flex-direction: row;
        align-items: center;
        height: 100%;
    }
    .border-upload-container {
        border: 1px solid #ccc;
        background-color: #ebebeb;
        border-radius: 5px;
        height: 35px;
        padding: 0 10px 0 0;
        cursor: pointer;
    }
    .pdf-icons{
        padding: 0 10px;
    }
    .bg-white {
        background-color: white !important;
    }
</style>
