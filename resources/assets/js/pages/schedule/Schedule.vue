<template>
  <form @submit.prevent="onSubmit">
    <practitioner v-if="step === 1" />
    <!-- <phone v-if="step === 2" /> -->
    <datetime v-if="step === 2" :availability="practitioner_availability" />
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
        step: 0,
        practitioner: null,
        practitioner_availability: [],
        appointmentDate: '',
        env: this.$root.$data.environment,
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

        // send the appointmentDate up so other components can get to it
        this.$root.$data.sharedState.appointmentDate = this.appointmentDate;

        // build the data for the submission
        const appointmentData = {
          appointment_at: this.appointmentDateUTC,
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
    },
    computed: {
      appointmentDateUTC() {
        return moment(this.appointmentDate).utc().format('YYYY-MM-DD HH:mm:ss');
      }
    },
    mounted() {
      // Incrementing the step on the tick after mount resolves the DOM
      // race condition and enables the initial fade-in
      Vue.nextTick(() => {
        this.step = this.step === 0 ? 1 : this.step;
      })
    }
  }
</script>
