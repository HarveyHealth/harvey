<template>
  <div class="records-container">  
                        
        <div class="top-soap-note">
            <h7 class="card-header top-header">Subject</h7>
            <textarea :maxlength="2048" v-model="subjective" class="input--textarea soap-textarea" placeholder="Enter your text..." />
        </div>

        <div class="mid-soap-note">
            <h7 class="card-header mid-header">Objective</h7>
            <textarea :maxlength="2048" v-model="objective" class="input--textarea soap-textarea" placeholder="Enter your text..." />
        </div>

        <div class="mid-soap-note">
            <h7 class="card-header mid-header">Assessment</h7>
            <textarea :maxlength="2048" v-model="assessment" class="input--textarea soap-textarea" placeholder="Enter your text..."/>
        </div>

        <div class="soap-divider">
            - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - FIELDS BELOW THIS LINE VISIBLE TO PATIENT  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        </div>

        <div class="top-soap-note">
            <h7 class="card-header top-header">Treatment</h7>
            <textarea :maxlength="2048" v-model="plan" class="input--textarea soap-textarea" placeholder="Enter your text..." />
        </div>
            
        <div class="inline-centered padding15">
            <button @click="createSoapNote()" :disabled="!subjective || !objective || !assessment || !plan" class="button margin35">Save Changes</button>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
export default {
    props: ['patient'],
    data() {
      return {
          subjective: '',
          objective: '',
          assessment: '',
          plan: '',
      }
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
            let global = this.$root.$data.global;
            global.soapNotes[response.data.data.attributes.created_by_user_id] = global.soapNotes[response.data.data.attributes.created_by_user_id]
                ? global.soapNotes[response.data.data.attributes.created_by_user_id] : {};
            global.soapNotes[response.data.data.attributes.created_by_user_id][response.data.data.id] = global.soapNotes[response.data.data.attributes.created_by_user_id][response.data.data.id]
                ? global.soapNotes[response.data.data.attributes.created_by_user_id][response.data.data.id] : {};
              global.soapNotes[response.data.data.attributes.created_by_user_id][response.data.data.id] = response.data.data;
          })
      }
  }
}
</script>

<style lang="scss">
    .records-container {
        height: auto; 
        padding: 10px; 
        overflow-x: hidden; 
        overflow-y: scroll; 
        width: 100%;
    }
    .top-soap-note {
        width: 97%; 
        position: relative; 
        top: 25px;

        .top-header {
            height: 20px; 
            margin: 15px; 
            padding: 5px;
        }
    }
    .mid-soap-note {
        width: 97%; 
        position: relative; 
        top: 15px;

        .mid-header {
            height: 20px; 
            margin: 20px 15px; 
            padding: 5px;
        }
    }
    .soap-divider {
        color: #EDA1A6; 
        padding: 5px; 
        padding-top: 10px; 
        width: 97%; 
        margin: 0 20px;
    }
    .soap-textarea {
        min-height: 100px; 
        margin: 15px;
    }
    .padding15 {
        padding-bottom: 15px;
    }
    .margin35 {
        margin: 35px;
    }
</style>
