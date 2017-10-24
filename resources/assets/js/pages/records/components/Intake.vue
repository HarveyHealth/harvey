<template>
    <div>
        <div>
            <h2>Basic Info</h2>
            <ul>
                <li>
                    <span></span>
                </li>
            </ul>
        </div>
        <div>
            <h2>Medical History</h2>
            <ul>
                <li>
                    <span></span>
                </li>
            </ul>
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
            currentPatient: this.$root.$data.global.patientLookUp[this.$props.patient.id],
        }
    },
    questionsList() {
        return [
            {
                question: `What is your gender?`,
                answer: "",
            },
        ]
    }
}
</script>

<style lang="scss">

</style>
