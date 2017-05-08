<template>
  <div class="main-container">
    <div class="nav-bar">
      <nav class="admin-nav">
        <a class="admin-nav-link dashboard" href="#">
          <svg class="icon icon-person"><use xlink:href="#person" /></svg>
        </a>
      </nav>
      <a href="#" class="nav-bar-account">
        <svg class="harvey-mark"><use xlink:href="#harvey-mark" /></svg>
      </a>
    </div>
    <div class="main-content">
      <div class="main-header">
        <div class="container">
          <h1 class="title header-xlarge">Your Dashboard
            <a href="#" class="button main-action disabled">New Appointment</a>
          </h1>
        </div>
      </div>
      <div class="card-wrapper">
        <div class="card">
          <Appointments :user-type="userType"
                        :recent-appointments="recent_appointments"
                        :upcoming-appointments="upcoming_appointments"></Appointments>
        </div>
        <div class="card smaller">
          <div class="card-heading-container">
            <h2 class="card-header">Your Contact</h2>
          </div>
          <div class="card-content-container">
            <div class="card-content-wrap">
              <h3 class="card-contact-name">
                <svg class="icon-person"><use xlink:href="#small-person" /></svg>{{ patientName }}
              </h3>
            </div>
            <div v-if="upcoming_appointments.length > 0" class="card-content-wrap">
              <h4 class="card-contact-sublabel">Doctor</h4>
              <p class="card-contact-info">{{ upcoming_appointments[0].attributes.practitioner_name }}</p>
            </div>
            <div class="card-content-wrap">
              <h4 class="card-contact-sublabel">Email</h4>
              <p class="card-contact-info">{{ email }}</p>
              <h4 class="card-contact-sublabel">Zip</h4>
              <p class="card-contact-info">{{ zip }}</p>
              <h4 class="card-contact-sublabel">Phone</h4>
              <p class="card-contact-info">{{ phone }}</p>
              <h4 class="card-contact-sublabel">ID</h4>
              <p class="card-contact-info">#100{{ user_id }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <Flyout :details="appointment_details"></Flyout>
  </div>
</template>

<script>
  import Appointments from '../../appointments/Appointments.vue';
  import Flyout from './_components/Flyout.vue';
  import {capitalize, phone, hyperlink} from '../../filters/textformat.js';
  import Contact from '../../mixins/Contact';

  export default {
    name: 'dashboard',
    data() {
      return {
        appointment_details: {
          'appointment_at': '',
          'appointment_purpose': '',
          'doctor_name': '',
          'patient_email': '',
          'patient_name': '',
          'patient_phone': '',
        },
        patientName: Laravel.user.fullName, // because it's already there
        recent_appointments: [],
        upcoming_appointments: [],
      };
    },
    props: ['user', 'patient'],
    components: {
      Appointments,
      Flyout
    },
    methods: {
      viewAppointmentPage() {
        this.$eventHub.$emit('mixpanel', "View New Appointment Page");
      }
    },
    computed: {
      // The user information needs to be computed properties because the data
      // is coming from a promise and most likely will not be available when
      // Dashboard is fully mounted. Without this, lots of errors in the console.
      displayName() {
        if(this.user.attributes === undefined) {
          return '';
        } else {
          return `${this.user.attributes.first_name} ${this.user.attributes.last_name}`;
        }
      },
      email() {
        return this.user.attributes ? this.user.attributes.email : '';
      },
      phone() {
        return this.user.attributes ? phone(this.user.attributes.phone) : '';
      },
      user_id() {
        return this.user.id || '';
      },
      userType() {
        return Laravel.user.userType;
      },
      zip() {
        return this.user.attributes ? this.user.attributes.zip : '';
      }
    },
    created() {
      this.$http.get(this.$root.apiUrl + '/appointments?filter=upcoming&include=patient.user')
        .then((response) => {
          this.upcoming_appointments = response.data;
        });
      this.$http.get(this.$root.apiUrl + '/appointments?filter=recent&include=patient.user')
        .then((response) => {
          this.recent_appointments = response.data;
        });
    },
    mounted() {
      this.$eventHub.$on('appointmentSelected', (details) => {
        if (this.userType === 'patient') {
          details.patient_email = this.email;
          details.patient_name = this.patientName;
          details.patient_phone = this.phone;
        }
        this.appointment_details = details;
      })
    }
  }
</script>

<style lang="scss" scoped>
  .icon-person {
    height: 30px;
    margin-right: 7px;
    width: 30px;
    vertical-align: top;
  }
</style>
