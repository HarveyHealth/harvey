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
                        <select>
                            <option v-for="script in prescriptionList">{{ script.name }}</option>
                        </select>
                    </span>
                </div>
                <div class="width-175">
                    <label class="input__label">upload</label>
                    <div class="border-upload-container">
                        <div class="upload-container">
                            <i class="fa fa-book pdf-icons"></i>
                            <p class="pdf-upload-text">Prescription (PDF)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="record-image" v-if="!$parent.news">
            <iframe class="record-image" :src="prescriptionUrl" />
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
        prescriptionList() {
            return [{name: ''}].concat([]);
        },
        prescriptionUrl() {
            return this.$parent.propData.attributes.url;
        }
    },
    watch : {
        prescriptionUrl(val) {
            if (!val) {
                return this.$parent.propData.attributes.url;
            }
        }
    }
};
</script>
