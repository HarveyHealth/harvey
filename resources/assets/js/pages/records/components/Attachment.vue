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
            return [{name: ''}].concat(this.$parent.propData.attributes);
        }
    }
};
</script>
