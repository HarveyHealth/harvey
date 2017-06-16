<template>
  <div class="appointment">
    <div class="appointment_left">
      <template v-if="user_type == 'admin'">
        <p v-text="fullName"></p>
        <p class="appointment_doctor">Dr. {{ appointment.attributes.practitioner_name }}</p>
      </template>
      <template v-else-if="user_type == 'practitioner'">
        <p v-text="fullName"></p>
      </template>
      <template v-else>
        <p class="appointment_doctor">Dr. {{ appointment.attributes.practitioner_name }}</p>
      </template>
    </div>
    <div class="appointment_right">
      <div class="box">
        <p class="appointment_date">{{ localAppointmentTime }}</p>
      </div>
    </div>
  </div>
</template>

<script>
  import moment from 'moment-timezone';
  import { capitalize, phone, hyperlink } from '../../../utils/filters/textformat.js';
  import Contact from '../../../utils/mixins/Contact';

  export default {
    mixins: [Contact],
    props: ['appointment', 'patientData', 'userType'],
    data() {
      return {
        local_timezone: ''
      }
    },
    methods: {
      capitalize,
      phone,
      hyperlink,
    },
    computed: {
      localAppointmentTime() {
        return this.$root.addTimezone(moment.utc(this.appointment.attributes.appointment_at.date).local().format('ddd, MMM Do [at] h:mm a'));
      },
      fullName() {
        if (this.patientData.first_name) {
          return `${capitalize(this.patientData.first_name)} ${capitalize(this.patientData.last_name)}`;
        }
        return 'Patient';
      },
      user_type() {
        return this.userType;
      }
    },
    mounted() {
      this.local_timezone = moment.tz.guess();
    }
  }
</script>

<style lang="scss" scoped>

</style>
