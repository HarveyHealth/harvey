<template>
  <form @submit.prevent="onSubmit">
    <practitioner v-if="step === 1" />
    <phone v-if="step === 2" />
    <datetime v-if="step === 3" :availability="practitioner_availability" />
  </form>
</template>

<script>
  import Practitioner from './_components/Practitioner.vue';
  import Phone from './_components/Phone.vue';
  import DateTime from './_components/DateTime.vue';

  import moment from 'moment';

  export default {
    name: 'Schedule',
    data() {
      return {
        title: "We're starting the process",
        subtitle: 'Before talking to a doctor, we need some basic contact info, your choice of practitioner and a date/time you are available for a consultation. This should take less than 5 minutes.',
        step: 1,
        practitioner: null,
        practitioner_availability: [],
        appointmentDate: '',
      }
    },
    components: {
      'datetime': DateTime,
      'phone': Phone,
      'practitioner': Practitioner,
    },
    methods: {
      next() {
        this.step ++; // simply increment the steps to move through the form states
      },
      onSubmit() {

        // build the data for the submission
        const appointmentData = {
          appointment_at: this.appointmentDate,
          reason_for_visit: 'blank',
          practitioner_id: this.practitioner,
        }

        axios.post(`api/v1/appointments`, appointmentData)
        .then(response => {
          this.$router.push('/confirmation');
        })
        .catch(error => {
          console.log(error.response);
        });
      }
    }
  }
</script>
