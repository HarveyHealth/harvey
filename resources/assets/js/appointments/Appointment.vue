<template>
  <div :class="{ appointment: true, isactive: isActive }" @click="showDetails">
    <div class="appointment_left">
      <template v-if="userType == 'admin' || userType == 'practitioner'">
        <p class="color-greylight">Patient</p>
        <p>{{ capitalize(patient.first_name) }} {{ capitalize(patient.last_name) }}</p>
        <p>
          <a :href="hyperlink(patient.phone, 'phone')">
            <span class="icon is-small"><span class="fa fa-phone"></span></span>
            <span>{{ phone(patient.phone) }}</span>
          </a>
        </p>
        <p>
          <a :href="hyperlink(patient.email, 'email')">
            <span class="icon is-small"><span class="fa fa-envelope"></span></span>
            <span>{{ patient.email }}</span>
          </a>
        </p>
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
    <!-- <div class="appointment_right">
      <div v-if="userType == 'patient'" class="title">
        <button class="appointment_button" @click="toggleContact">Reschedule</button>
      </div>
    </div> -->
  </div>
</template>

<script>
  import moment from 'moment-timezone';
  import {capitalize, phone, hyperlink} from '../filters/textformat.js';
  import Contact from '../mixins/Contact';

  export default {
    mixins: [Contact],
    props: ['appointment', 'userType'],
    data() {
      return {
        isActive: false,
        patient: {},
        local_timezone: 'America/Los_Angeles'
      }
    },
    methods: {
      capitalize,
      phone,
      hyperlink,
      showDetails() {
        this.$eventHub.$emit('appointmentSelected', {
          appointment_at: this.localAppointmentTime.format('ddd, MMM Do [at] h:mma'),
          appointment_purpose: this.appointment.attributes.reason_for_visit,
          doctor_name: this.appointment.attributes.practitioner_name,
        })
        this.isActive = true;
      }
    },
    computed: {
      localAppointmentTime() {
        let m = moment.utc(this.appointment.attributes.appointment_at.date, "YYYY-MM-DD h:i:s");
        return moment(m).tz(this.local_timezone);
      }
    },
    mounted() {
      this.local_timezone = moment.tz.guess();
      this.$eventHub.$on('appointmentSelected', () => {
        this.isActive = false;
      })
    }
  }
</script>

<style lang="scss" scoped>
  .appointment-wrapper:last-of-type .appointment {
    border-bottom: none;
  }
    .appointment {
      border-bottom: 1px solid #E4EAEC;
      cursor: pointer;
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
