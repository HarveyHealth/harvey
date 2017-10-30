<template>
    <div class="records-container">

        <div class="top-soap-note">
            <label name="Subject" class="card-header top-header">Subject</label>
            <textarea v-model="subjectiveTA" class="input--textarea soap-textarea" placeholder="Enter your text..." />
        </div>

        <div class="mid-soap-note">
            <label name="Objective" class="card-header mid-header">Objective</label>
            <textarea v-model="objectiveTA" class="input--textarea soap-textarea" placeholder="Enter your text..." />
        </div>

        <div class="mid-soap-note">
            <label name="Assessment" class="card-header mid-header">Assessment</label>
            <textarea v-model="assessmentTA" class="input--textarea soap-textarea" placeholder="Enter your text..."/>
        </div>

        <div class="soap-divider">
            - - - - - - - - - - - - - - - - - - - - FIELDS BELOW THIS LINE VISIBLE TO PATIENT  - - - - - - - - - - - - - - - - - - - -
        </div>

        <div class="top-soap-note">
            <label name="Treatment" class="card-header top-header">Plan/Treatment</label>
            <textarea v-model="planTA" class="input--textarea soap-textarea" placeholder="Enter your text..." />
        </div>

        <div class="inline-centered padding15">
            <button @click="submit()" :disabled="!subjectiveTA || !objectiveTA || !assessmentTA || !planTA" class="button margin35">Save Changes</button>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { capitalize } from 'lodash';
import moment from 'moment';
export default {
    props: {
        patient: Object
    },
    data() {
        return {
            subjectiveTA: null,
            objectiveTA: null,
            assessmentTA: null,
            planTA: null
        };
    },
    methods: {
        setSubjectiveTA(data) {
            this.subjectiveTA = data;
        },
        setObjectiveTA(data) {
            this.objectiveTA = data;
        },
        setAssessmentTA(data) {
            this.assessmentTA = data;
        },
        setPlanTA(data) {
            this.planTA = data;
        },
        createSoapNote() {
            axios.post(`${this.$root.$data.apiUrl}/patients/${this.$props.patient.id}/soap_notes`, {
                subjective: this.subjectiveTA,
                objective: this.objectiveTA,
                assessment: this.assessmentTA,
                plan: this.planTA
            })
            .then(response => {
                let object = {};
                let returns = response.data.data;
                this.$parent.soap_notes[returns.id] = returns;
                object.data = returns;
                object.id = returns.id;
                object.date = moment(returns.attributes.created_at.date).format('dddd, MMM Do YYYY');
                object.original_date = returns.attributes.created_at.date;
                object.doctor = returns.attributes.doctor_name;
                object.type = returns.type.split('_').map(e => capitalize(e)).join(' ');
                this.$parent.timeline.shift(object);
            });
        },
        editSoapNote() {
            axios.patch(`${this.$root.$data.apiUrl}/patients/${this.$props.patient.id}/soap_notes`, {
                subjective: this.subjectiveTA,
                objective: this.objectiveTA,
                assessment: this.assessmentTA,
                plan: this.planTA
            })
            .then(response => {
                this.$parent.soap_notes[response.data.data.id] = response.data.data;
            });
        },
        submit() {
            return this.$parent.news ? this.createSoapNote() : this.editSoapNote();
        }
    },
    computed: {
        subjective() {
            let data = this.$parent.news ? '' : this.$parent.propData.attributes.subjective;
            this.setSubjectiveTA(data);
            return data;
        },
        objective() {
            let data = this.$parent.news ? '' : this.$parent.propData.attributes.objective;
            this.setObjectiveTA(data);
            return data;
        },
        assessment() {
            let data = this.$parent.news ? '' : this.$parent.propData.attributes.assessment;
            this.setAssessmentTA(data);
            return data;
        },
        plan() {
            let data = this.$parent.news ? '' : this.$parent.propData.attributes.plan;
            this.setPlanTA(data);
            return data;
        }
    }
};
</script>
