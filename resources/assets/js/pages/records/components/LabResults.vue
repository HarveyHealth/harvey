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
            <div>
                <label class="input__label">lab name</label>
                <div></div>
            </div>
            <div>
                <label class="input__label">lab test</label>
                <div></div>
            </div>
            <div>
                <label class="input__label">upload</label>
                <div>

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
    }
}
</script>

<style lang="scss">
    .records-spacing {
        display: flex;
        justify-content: space-around;
        width: 60%;
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
</style>
