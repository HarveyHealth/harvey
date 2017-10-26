<template>
    <div class="records-container">

        <div class="top-soap-note">
            <label name="Subject" class="card-header top-header">Subject</label>
            <textarea v-model="subjective" class="input--textarea soap-textarea" placeholder="Enter your text..." />
        </div>

        <div class="mid-soap-note">
            <label name="Objective" class="card-header mid-header">Objective</label>
            <textarea v-model="objective" class="input--textarea soap-textarea" placeholder="Enter your text..." />
        </div>

        <div class="mid-soap-note">
            <label name="Assessment" class="card-header mid-header">Assessment</label>
            <textarea v-model="assessment" class="input--textarea soap-textarea" placeholder="Enter your text..."/>
        </div>

        <div class="soap-divider">
            - - - - - - - - - - - - - - - - - - - - - - - - - FIELDS BELOW THIS LINE VISIBLE TO PATIENT  - - - - - - - - - - - - - - - - - - - - - - - - -
        </div>

        <div class="top-soap-note">
            <label name="Treatment" class="card-header top-header">Plan/Treatment</label>
            <textarea v-model="plan" class="input--textarea soap-textarea" placeholder="Enter your text..." />
        </div>

        <div class="inline-centered padding15">
            <button @click="createSoapNote()" :disabled="!subjective || !objective || !assessment || !plan" class="button margin35">Save Changes</button>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { isEmpty } from 'lodash';
export default {
    props: {
      patient: Object
    },
    data() {
      return {
          
      };
  },
  methods: {
      createSoapNote() {
          axios.post(`${this.$root.$data.apiUrl}/patients/${this.$props.patient.id}/soap_notes`, {
              subjective: this.subjective,
              objective: this.objective,
              assessment: this.assessment,
              plan: this.plan
          })
          .then(response => {
              this.$parent.soap_notes[response.data.data.id] = response.data.data;
              this.$parent.timeline.push(response.data.data);
          });
      }
    },
    computed: {
        subjective() {
            return this.$parent.news ? '' : this.$parent.propData.attributes.subjective;
        },
        objective() {
            return this.$parent.news ? '' : this.$parent.propData.attributes.objective;
        },
        assessment() {
            return this.$parent.news ? '' : this.$parent.propData.attributes.assessment;
        },
        plan() {
            return this.$parent.news ? '' : this.$parent.propData.attributes.plan;
        }
    }
};
</script>
