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
        <p class="appointment_date">{{ localAppointmentTime.format('ddd, MMM Do [at] h:mma') }}</p>
      </div>
    </div>
  </div>
</template>

<script>
  import moment from 'moment-timezone';
  import { capitalize, phone, hyperlink } from '../filters/textformat.js';
  import Contact from '../mixins/Contact';

  export default {
    mixins: [Contact],
    props: ['appointment', 'patientData', 'userType'],
    data() {
      return {
        local_timezone: 'America/Los_Angeles'
      }
    },
    methods: {
      capitalize,
      phone,
      hyperlink,
    },
    computed: {
      localAppointmentTime() {
        let m = moment.utc(this.appointment.attributes.appointment_at.date, "YYYY-MM-DD h:i:s");
        return moment(m).tz(this.local_timezone);
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
  .appointment-wrapper:last-of-type .appointment {
    border-bottom: none;
  }
    .appointment {
      border-bottom: 1px solid #E4EAEC;
      overflow: hidden;
      padding: 20px 30px;
    }
    .appointment_left {
      float: left;

      p {
        margin-bottom: 0;
      }
    }
    .appointment_right {
      float: right;
      p {
        margin-bottom: 0;
      }
    }
    .appointment_date, .appointment_doctor {
      font-weight: 300;
      letter-spacing: 1px;
      font-size: 18px;
    }
</style>
