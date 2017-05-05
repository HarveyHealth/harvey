<template>
  <div :class="{ appointment: true, isactive: isActive }" @click="showDetails">
    <div class="appointment_left">
      <template v-if="userType == 'admin' || userType == 'practitioner'">
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
        patient: {
          first_name: '',
          last_name: '',
          phone: '',
          email: '',
        },
        user_data: {},
        local_timezone: 'America/Los_Angeles'
      }
    },
    methods: {
      capitalize,
      phone,
      hyperlink,
      showDetails() {
        let details = {
          appointment_at: this.localAppointmentTime.format('ddd, MMM Do [at] h:mma'),
          appointment_purpose: this.appointment.attributes.reason_for_visit,
          doctor_name: this.appointment.attributes.practitioner_name,
        }
        if (this.userType === 'admin') {
          details.patient_name = this.fullName;
          details.patient_email = this.patient.email;
          details.patient_phone = this.patient.phone;
        }
        this.$eventHub.$emit('appointmentSelected', details);
        this.isActive = true;
      }
    },
    computed: {
      localAppointmentTime() {
        let m = moment.utc(this.appointment.attributes.appointment_at.date, "YYYY-MM-DD h:i:s");
        return moment(m).tz(this.local_timezone);
      },
      fullName() {
        if (this.patient.first_name) {
          return `${capitalize(this.patient.first_name)} ${capitalize(this.patient.last_name)}`;
        }
        return 'Patient';
      }
    },
    created() {
      // axios.get(`/api/v1/patients/${this.appointment.attributes.patient_id}`).then(response => {
      //   if (response) {
      //     axios.get(`/api/v1/users/${response.data.data.attributes.user_id}`).then(response => {
      //       console.log(response.data.data);
      //       this.patient.first_name = response.data.data.attributes.first_name;
      //       this.patient.last_name = response.data.data.attributes.last_name;
      //       this.patient.phone = response.data.data.attributes.phone;
      //       this.patient.email = response.data.data.attributes.email;
      //     })
      //   }
      // }).catch(error => {
      //
      // })
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
