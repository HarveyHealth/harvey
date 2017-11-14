<template>
    <div id="intake">
        <div class="intake-padding">
            <h2 class="no-margin">Intake Information</h2>
            <ul>
                <li v-for="intake in questionsList">
                    <div>
                        <span v-html="intake.question" />
                        <span class="error-color">{{ intake.answer }}</span>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import _ from 'lodash';
export default {
    props: {
        patient: Object
    },
    data() {
        return {
            currentPatient: this.$root.$data.global.patientLookUp[this.$props.patient.id],
        };
    },
    computed: {
        questionsList() {
            let quest = {};
            let props = this.$parent.propData;
            props.attributes.questions.forEach(e => {
                quest[e.id] = {};
                quest[e.id].question = e.question;
            });
            props.attributes.responses.forEach(e => {
                _.each(e.answers, (answer, id) => {
                    quest[id].answer = answer;
                });
            });
            return Object.values(quest);
        }
    }
};
</script>

<style lang="scss">
    .no-margin {
        margin: 0 !important;
    }
    .intake-padding {
        padding: 10px 20px;
    }
</style>
