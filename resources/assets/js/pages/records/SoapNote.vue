<template>
  <div style="height: 800px; padding: 10px; overflow-x: hidden; overflow-y: scroll;">  
                        
        <div style="width: 97%; position: relative; top: 25px;">
            <h7 class="card-header" style="height: 20px; margin: 15px; padding: 5px;">Subject</h7>
            <textarea v-model="subjective" class="input--textarea" placeholder="Enter your text..." style="min-height: 100px; margin: 15px;" />
        </div>

        <div style="width: 97%; position: relative; top: 15px;">
            <h7 class="card-header" style="height: 20px; margin: 20px 15px; padding: 5px;">Objective</h7>
            <textarea v-model="objective" class="input--textarea" placeholder="Enter your text..." style="min-height: 100px; margin: 15px;" />
        </div>

        <div style="width: 97%; position: relative; top: 15px;">
            <h7 class="card-header" style="height: 20px; margin: 20px 15px; padding: 5px;">Assessment</h7>
            <textarea v-model="assessment" class="input--textarea" placeholder="Enter your text..." style="min-height: 100px; margin: 15px;"/>
        </div>

        <div style="color: #EDA1A6; padding: 5px; padding-top: 10px; width: 97%; margin: 0 20px;">
            - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - FIELDS BELOW THIS LINE VISIBLE TO PATIENT  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        </div>

        <div style="width: 97%; position: relative; top: 25px;">
            <h7 class="card-header" style="height: 20px; margin: 15px; padding: 5px;">Treatment</h7>
            <textarea v-model="plan" class="input--textarea" placeholder="Enter your text..." style="min-height: 100px; margin: 15px;" />
        </div>
            
        <div class="inline-centered">
            <button @click="createSoapNote()" :disabled="!subjective || !objective || !assessment || !plan" class="button" style="margin-top: 35px;">Save Changes</button>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
export default {
    props: ['patient'],
    data() {
      return {
          subjective: null,
          objective: null,
          assessment: null,
          plan: null,
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

          })
      }
  }
}
</script>


<style>

</style>
