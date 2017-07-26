<template>
  <div class="main-container" :class="userClass">
    <div class="main-content">
      <div class="main-header">
        <div class="container container-backoffice">
          <h1 class="title header-xlarge">{{ dashboardTitle }}</h1>
        </div>
      </div>

      <div class="card-wrapper alert" v-if="viewableIntakeAlert">
        <div class="card">
          <div class="card-alert-text">
            <h3>Patient Intake Form</h3>
            <p>Please note: You must finish your patient intake form before your first appointment.</p>
          </div>
          <div class="card-alert-button">
            <a :href="'https://goharvey.intakeq.com/new/Qqy0mI/DpjPFg?harveyID=' + user_id" target="_blank"><button class="button is-primary is-outlined">Edit Intake Form</button></a>
          </div>
        </div>
      </div>

      <div class="card-wrapper">

        <div class="card card-panel">
          <DashboardAppointments :user-type="userType"
            :recent-appointments="recent_appointments"
            :upcoming-appointments="upcoming_appointments"></DashboardAppointments>
        </div>

        <div class="card card-panel" v-if="userType === 'patient'">
          <div class="card-heading-container">
            <h2 class="card-header">Your Doctor</h2>
          </div>
          <div class="card-content-container">

            <div class="card-content-wrap">
              <h3 class="card-contact-name">
                <img class="card-avatar" :src="practitioner.avatar">
                {{ practitioner.name }}
              </h3>
            </div>

            <div class="card-content-wrap">
              <p class="appointment_doctor">{{ practitioner.description }}</p>
<!--          <h4 class="card-contact-sublabel" v-if="email">Email</h4>
              <p class="card-contact-info"><a href="mailto:support@goharvey.com">support@goharvey.com</a></p>
              <h4 class="card-contact-sublabel" v-if="phone">Phone</h4>
              <p class="card-contact-info" v-if="phone"><a href="tel:800-690-9989">(800) 690-9989</a></p> -->
            </div>
          </div>
        </div>

      </div>
      <div class="card-wrapper">
        <div class="card smaller">
          <div class="card-heading-container">
            <h2 class="card-header">Your Info</h2>
          </div>
          <div class="card-content-container">
            <div class="card-content-wrap" v-if="patientName">
              <h3 class="card-contact-name">
                <svg class="icon-person"><use xlink:href="#small-person" /></svg>{{ displayName }}
              </h3>
            </div>
            <div class="card-content-wrap">
              <h4 class="card-contact-sublabel" v-if="email">Email</h4>
              <p class="card-contact-info" v-if="email"><a :href="'mailto:'+email">{{ email }}</a></p>
              <h4 class="card-contact-sublabel" v-if="zip">Zip</h4>
              <p class="card-contact-info" v-if="zip">{{ zip }}</p>
              <h4 class="card-contact-sublabel" v-if="phone">Phone</h4>
              <p class="card-contact-info" v-if="phone"><a :href="'tel:'+phone">{{ phone }}</a></p>
              <h4 class="card-contact-sublabel" v-if="user_id">ID</h4>
              <p class="card-contact-info" v-if="user_id">#{{ user_id }}</p>
            </div>
          </div>
        </div>
        <div class="card smaller">
          <div class="card-heading-container">
            <h2 class="card-header">Account Manager</h2>
          </div>
          <div class="card-content-container">
            <div class="card-content-wrap" v-if="patientName">
              <h3 class="card-contact-name">
                <svg class="icon-person"><use xlink:href="#small-person" /></svg>Sandra Walker
              </h3>
            </div>
            <div class="card-content-wrap">
              <h4 class="card-contact-sublabel">Support</h4>
              <p class="card-contact-info"><a href="mailto:support@goharvey.com">support@goharvey.com</a></p>
              <h4 class="card-contact-sublabel">Phone</h4>
              <p class="card-contact-info"><a href="tel:800-690-9989">800-690-9989</a></p>
              <h4 class="card-contact-sublabel">Available</h4>
              <p class="card-contact-info">Mon-Fri 9am-6pm PST</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import DashboardAppointments from './components/DashboardAppointments.vue';
  import { capitalize, phone, hyperlink } from '../../utils/filters/textformat.js';
  import Contact from '../../utils/mixins/Contact';
  import combineAppointmentData from '../../utils/methods/combineAppointmentData';
  import getAppointments from '../../utils/methods/getAppointments';

  export default {
    name: 'dashboard',
    data() {
      return {
        patientName: Laravel.user.fullName, // because it's already there
        flag: false
      };
    },
    props: ['user', 'patient'],
    components: {
      DashboardAppointments,
    },
    methods: {
      viewAppointmentPage() {
        // add tracking for Appointments Page view here
      }
    },
    computed: {
      dashboardTitle() {
        if (this.userType === 'admin') {
          return 'Admin Dashboard';
        } else {
          return 'Your Dashboard';
        }
      },
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
      practitioner() {
        if (this.$root.$data.global.practitioners.length) {
          const name = Laravel.user.doctor_name;
          return this.$root.$data.global.practitioners.filter(dr => {
            return dr.info.name === name;
          }).map(dr => {
            const typeAbbr = dr.info.type_name
              .split(' ')
              .map(part => part.charAt(0))
              .join('');
            return {
              avatar: dr.info.picture_url,
              description: dr.info.description,
              name: `${dr.name}, ${typeAbbr}`
            }
          })[0];
        } else {
          return {
            avatar: '#',
            description: 'Loading practitioner information',
            name: ''
          }
        }

      },
      recent_appointments() {
        return this.$root.$data.global.recent_appointments || [];
      },
      upcoming_appointments() {
        return this.$root.$data.global.upcoming_appointments || [];
      },
      userClass() {
        return {
          [`is-${this.userType}`]: true
        }
      },
      user_id() {
        return this.user.id || '';
      },
      userType() {
        return Laravel.user.user_type;
      },
      viewableIntakeAlert() {
        return !this.$root.$data.global.loadingAppointments &&
               !this.$root.$data.global.appointments.length &&
               this.userType === 'patient';
      },
      zip() {
        return this.user.attributes ? this.user.attributes.zip : '';
      },
      appointments() {
        return this.$root.$data.global.appointments;
      }
    },
    beforeMount() {
      let flag = localStorage.getItem('signed up');
      if (flag) {
        localStorage.removeItem('signed up');
      }
    },
    mounted() {
      this.$root.$data.global.currentPage = 'dashboard';
      if (localStorage.getItem('signed up')) return null;
      if (this.$root.isOnProduction()) {
        // Add tracking for Dashboard here
      }
    }
  }
</script>
