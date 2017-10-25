<template>
    <div class="lab-body">
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
                <span class="custom-select bg-white">
                    <select>
                        <option v-for="attach in attachmentList">{{ attach.name }}</option>
                    </select>
                </span>
            </div>
            <div class="width-175">
                <label class="input__label">upload</label>
                <div class="border-upload-container">
                    <div class="upload-container">
                        <i class="fa fa-book pdf-icons"></i>
                        <p class="pdf-upload-text">Attachment (PDF)</p>
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
        attachmentList() {
            return [{name: ''}].concat([]);
        }
    }
};
</script>

<style lang="scss">
    .records-spacing {
        display: flex;
        justify-content: space-around;
        width: 500px;
    }
    .p-spacing {
        padding: 25px;
    }
    .width-175 {
        width: 175px;
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
        background-color: #ececec;
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
