<template>
  <div>

    <div class="header nav">
        <div class="container">
            <div class="nav-left">
                <a href="#" class="nav-item">
                    <div class="logo-wrapper">
                        <svg class="harvey-mark"><use xlink:href="#harvey-logo" /></svg>
                    </div>
                </a>
            </div>
            <div class="nav-right">
                <span class="nav-item">
                    <a href="tel:800-690-9989" class="button is-primary is-outlined">(800) 690-9989</a>
                </span>
            </div>
        </div>
    </div>

    <form @submit.prevent="onSubmit" v-if="!$root.initialAppointmentComplete">
      <practitioner v-if="step === 1" />
      <phone v-if="step === 2" />
      <datetime v-if="step === 3" :availability="_availability" />
    </form>
    <router-view v-if="$root.initialAppointmentComplete" />
  </div>
</template>

<script>
  import Practitioner from './components/Practitioner.vue';
  import Phone from './components/Phone.vue';
  import DateTime from './components/DateTime.vue';

  import moment from 'moment';

  export default {
    name: 'Schedule',
    data() {
      return {
        title: "We're starting the process",
        subtitle: 'Before talking to a doctor, we need some basic contact info, your choice of practitioner and a date/time you are available for a consultation. This should take less than 5 minutes.',
        practitioner: null,
        practitioner_availability: [],
        _availability: [],
        practitioner_id: null,
        appointmentDate: '',
        step: 0,
        firstname: '',
        lastname: '',
        phone: '',
        selectedDate: null,
        selectedTime: null,
        selectedTimeBool: false,
        selectedDateBool: false,
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
      previous() {
        this.step --; // simply decrement the steps to move through the form states
      },
      onSubmit() {

        // build the data for the submission
        const appointmentData = {
          appointment_at: this.$root.initialAppointment.appointment_at,
          reason_for_visit: 'First Appointment',
          practitioner_id: this.$root.initialAppointment.practitioner_id,
        }

        axios.post(`/api/v1/appointments`, appointmentData)
          .then(response => {
            this.$root.$data.appointmentData = response.data;
            this.$root.initialAppointmentComplete = true;
            this.$router.push('/confirmation');
          })
          .catch(error => {
            console.log(error.response);
          });
      }
    },
    computed: {
      appointmentDateUTC() {
        return moment(this.appointmentDate, 'YYYY-MM-DD HH:mm:ss a').utc().format('YYYY-MM-DD HH:mm:ss');
      }
    },
    mounted() {
      // Incrementing the step on the tick after mount resolves the DOM
      // race condition and enables the initial fade-in
      Vue.nextTick(() => {
        this.step = this.step === 0 ? 1 : this.step;
      })

      let flag = localStorage.getItem('signed up')
      if (flag) {
        localStorage.removeItem('signed up')
      }
    }
  }
</script>
