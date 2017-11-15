<template>
    <div class="records-container">

        <div class="top-soap-note">
            <label name="Subject" class="card-header top-header">Subjective</label>
            <textarea @keydown="keyDownTextarea" v-model="subjectiveTA" class="input--textarea soap-textarea" placeholder="Enter your text..." />
        </div>

        <div class="mid-soap-note">
            <label name="Objective" class="card-header mid-header">Objective</label>
            <textarea @keydown="keyDownTextarea" v-model="objectiveTA" class="input--textarea soap-textarea" placeholder="Enter your text..." />
        </div>

        <div class="mid-soap-note">
            <label name="Assessment" class="card-header mid-header">Assessment</label>
            <textarea @keydown="keyDownTextarea" v-model="assessmentTA" class="input--textarea soap-textarea" placeholder="Enter your text..."/>
        </div>

        <div class="soap-divider">
            - - - - - - - - - - - - - - - - - - - - FIELDS BELOW THIS LINE VISIBLE TO PATIENT  - - - - - - - - - - - - - - - - - - - -
        </div>

        <div class="top-soap-note">
            <label name="Treatment" class="card-header top-header">Plan/Treatment</label>
            <textarea @keydown="keyDownTextarea" v-model="planTA" class="input--textarea soap-textarea" placeholder="Enter your text..." />
        </div>

        <div class="inline-centered padding15">
            <button @click="submit()" :disabled="disabled || !subjectiveTA || !objectiveTA || !assessmentTA || !planTA" class="button margin35">Save Changes</button>
            <button v-if="!$parent.news" @click="deleteModal()" class="button bg-danger margin35">Delete Note</button>
        </div>

        <Modal
            :active="deleteModalActive"
            :onClose="modalClose"
            class="modal-wrapper"
        >
            <div class="card-content-wrap">
                <div class="inline-centered">
                    <h1 class="header-xlarge">
                        <span class="text">Delete SOAP Note</span>
                    </h1>
                    <p>Are you sure you want to delete this soap note?</p>
                    <div class="button-wrapper">
                        <button class="button button--cancel" @click="modalClose">Cancel</button>
                        <button class="button" @click="deleteNote">Yes, Confirm</button>
                    </div>
                </div>
            </div>
        </Modal>

    </div>
</template>

<script>
import axios from 'axios';
import { capitalize } from 'lodash';
import moment from 'moment';
import Modal from '../../../commons/Modal.vue';
export default {
    props: {
        patient: Object
    },
    components: {
        Modal
    },
    data() {
        return {
            subjectiveTA: null,
            objectiveTA: null,
            assessmentTA: null,
            planTA: null,
            disabled: true,
            deleteModalActive: false
        };
    },
    methods: {
        keyDownTextarea() {
            this.disabled = false;
        },
        setSubjectiveTA(data) {
            this.subjectiveTA = data;
            this.disabled = true;
        },
        setObjectiveTA(data) {
            this.objectiveTA = data;
            this.disabled = true;
        },
        deleteModal() {
            this.deleteModalActive = true;
        },
        modalClose() {
            this.deleteModalActive = false;
        },
        setAssessmentTA(data) {
            this.assessmentTA = data;
            this.disabled = true;
        },
        setPlanTA(data) {
            this.planTA = data;
            this.disabled = true;
        },
        deleteNote() {
            axios.delete(`${this.$root.$data.apiUrl}/soap_notes/${this.$parent.propData.id}`)
                .then(() => {
                    this.deleteModalActive = false;
                    this.$parent.page = 0;
                    this.$parent.index = null;
                    this.$parent.timeline = [];
                    this.$parent.loading = true;
                    this.$parent.getTimelineData();
                    this.$parent.notificationMessage = "Successfully deleted!";
                    this.$parent.notificationActive = true;
                    setTimeout(() => this.$parent.notificationActive = false, 3000);
                });
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
                object.doctor = returns.attributes.doctor_name || "No Doctor";
                object.type = returns.type.split('_').map(e => capitalize(e)).join(' ');
                this.$parent.timeline = [object].concat(this.$parent.timeline);
                this.$parent.news = false;
                this.$parent.setIndex(0);
                this.$parent.propData = returns;
                this.$parent.notificationMessage = "Successfully added!";
                this.$parent.notificationActive = true;
                setTimeout(() => this.$parent.notificationActive = false, 3000);
            });
        },
        editSoapNote() {
            axios.patch(`${this.$root.$data.apiUrl}/soap_notes/${this.$parent.propData.id}`, {
                subjective: this.subjectiveTA,
                objective: this.objectiveTA,
                assessment: this.assessmentTA,
                plan: this.planTA
            })
            .then(response => {
                this.$parent.soap_notes[response.data.data.id] = response.data.data;
                this.$parent.notificationMessage = "Successfully updated!";
                this.$parent.notificationActive = true;
                setTimeout(() => this.$parent.notificationActive = false, 3000);
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
    },
    watch: {
        subjective(val) {
            if (!val) {
                let data = this.$parent.news ? '' : this.$parent.propData.attributes.subjective;
                this.setSubjectiveTA(data);
                return data;
            }
        },
        objective(val) {
            if (!val) {
                let data = this.$parent.news ? '' : this.$parent.propData.attributes.objective;
                this.setObjectiveTA(data);
                return data;
            }
        },
        assessment(val) {
            if (!val) {
                let data = this.$parent.news ? '' : this.$parent.propData.attributes.assessment;
                this.setAssessmentTA(data);
                return data;
            }
        },
        plan(val) {
            if (!val) {
                let data = this.$parent.news ? '' : this.$parent.propData.attributes.plan;
                this.setPlanTA(data);
                return data;
            }
        }
    }
};
</script>
