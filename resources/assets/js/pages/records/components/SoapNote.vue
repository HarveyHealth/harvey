<template>
    <div class="records-container">

        <div class="top-soap-note">
            <label name="Subject" class="card-header top-header">Subject</label>
            <textarea v-model="subjectiveTextarea" class="input--textarea soap-textarea" placeholder="Enter your text..." />
        </div>

        <div class="mid-soap-note">
            <label name="Objective" class="card-header mid-header">Objective</label>
            <textarea v-model="objectiveTextarea" class="input--textarea soap-textarea" placeholder="Enter your text..." />
        </div>

        <div class="mid-soap-note">
            <label name="Assessment" class="card-header mid-header">Assessment</label>
            <textarea v-model="assessmentTextarea" class="input--textarea soap-textarea" placeholder="Enter your text..."/>
        </div>

        <div class="soap-divider">
            - - - - - - - - - - - - - - - - - - - - - - - - - FIELDS BELOW THIS LINE VISIBLE TO PATIENT  - - - - - - - - - - - - - - - - - - - - - - - - -
        </div>

        <div class="top-soap-note">
            <label name="Treatment" class="card-header top-header">Plan/Treatment</label>
            <textarea v-model="planTextarea" class="input--textarea soap-textarea" placeholder="Enter your text..." />
        </div>

        <div class="inline-centered padding15">
            <button @click="submit()" :disabled="!subjectiveTextarea || !objectiveTextarea || !assessmentTextarea || !planTextarea" class="button margin35">Save Changes</button>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
export default {
    props: {
      patient: Object
    },
    data() {
      return {
          subjectiveTextarea: '',
          objectiveTextarea: '',
          assessmentTextarea: '',
          planTextarea: ''
      };
  },
  methods: {
      createSoapNote() {
          axios.post(`${this.$root.$data.apiUrl}/patients/${this.$props.patient.id}/soap_notes`, {
              subjective: this.subjectiveTextarea,
              objective: this.objectiveTextarea,
              assessment: this.assessmentTextarea,
              plan: this.planTextarea
          })
          .then(response => {
              this.$parent.soap_notes[response.data.data.id] = response.data.data;
              this.$parent.timeline.push(response.data.data);
          });
      },
      editSoapNote() {
          axios.patch(`${this.$root.$data.apiUrl}/patients/${this.$props.patient.id}/soap_notes`, {
              subjective: this.subjectiveTextarea,
              objective: this.objectiveTextarea,
              assessment: this.assessmentTextarea,
              plan: this.planTextarea
          })
          .then(response => {
              this.$parent.soap_notes[response.data.data.id] = response.data.data;
          });
      },
      submit() {
          return this.$parent.news ? createSoapNote() : editSoapNote();
      },
      setTextareas() {
          let self = this;
          return {
              subjective: (data) => {
                  self.subjectiveTextarea = data;
              },
              objective: (data) => {
                  self.objectiveTextarea = data;
              },
              assessment: (data) => {
                  self.assessmentTextarea = data;
              },
              plan: (data) => {
                  self.planTextarea = data;
              }
          };
      }
    },
    computed: {
        subjective() {
            this.setTextareas().subjective(this.$parent.news ? '' : this.$parent.propData.attributes.subjective);
            return this.$parent.news ? '' : this.$parent.propData.attributes.subjective;
        },
        objective() {
            this.setTextareas().objective(this.$parent.news ? '' : this.$parent.propData.attributes.objective);
            return this.$parent.news ? '' : this.$parent.propData.attributes.objective;
        },
        assessment() {
            this.setTextareas().assessment(this.$parent.news ? '' : this.$parent.propData.attributes.assessment);
            return this.$parent.news ? '' : this.$parent.propData.attributes.assessment;
        },
        plan() {
            this.setTextareas().plan(this.$parent.news ? '' : this.$parent.propData.attributes.plan);
            return this.$parent.news ? '' : this.$parent.propData.attributes.plan;
        }
    }
};
</script>
