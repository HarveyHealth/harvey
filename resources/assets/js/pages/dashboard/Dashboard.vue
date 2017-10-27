<template>
  <div class="main-container" :class="'is-' + Config.user.info.user_type">
    <div class="main-content">
      <div class="main-header">
        <div class="container container-backoffice">
          <h1 class="heading-1">{{ Config.user.isAdmin ? 'Admin' : '' }} Dashboard</h1>
        </div>
      </div>
      <div class="card-wrapper">
        <div class="card card-panel">
          <DashboardAppointments :user-type="user.user_type" :upcoming-appointments="upcoming_appointments" />
        </div>
        <div class="card card-panel" v-if="Config.user.isPatient">
          <div class="card-heading-container">
            <h2 class="heading-2">Practitioner</h2>
          </div>
          <div v-if="practitioner.name" class="card-content-container">
            <div class="card-content-wrap">
              <h3 class="card-contact-name">
                <svg class="icon-person"><use xlink:href="#small-person" /></svg>{{ practitioner.name }}
              </h3>
            </div>
            <div class="card-content-wrap">
              <p>{{ practitioner.description }}</p>
            </div>
          </div>
          <div v-else class="card-content-container">
            <div class="card-empty-container">
              <p class="copy-muted-2 font-italic">{{ practitioner.status }}</p>
            </div>
          </div>
        </div>
      </div>
      <div class="card-wrapper">
        <div class="card smaller">
          <div class="card-heading-container">
            <h2 class="heading-2">Contact Info</h2>
          </div>
          <div class="card-content-container">
            <div class="card-content-wrap" v-if="user.first_name">
              <h3 class="card-contact-name">
                <svg class="icon-person"><use xlink:href="#small-person" /></svg>{{ Util.misc.fullName(user) }}
              </h3>
            </div>
            <div class="card-content-wrap">
              <h4 class="copy-muted-2 font-xs font-bold font-uppercase font-spaced" v-if="user.first_name">Name</h4>
              <p class="card-contact-info" v-if="user.first_name">{{ Util.misc.fullName(user) }}</p>
              <h4 class="copy-muted-2 font-xs font-bold font-uppercase font-spaced" v-if="user.email">Email</h4>
              <p class="card-contact-info" v-if="user.email"><a :href="'mailto:'+user.email">{{ user.email }}</a></p>
              <h4 class="copy-muted-2 font-xs font-bold font-uppercase font-spaced" v-if="user.phone">Phone</h4>
              <p class="card-contact-info" v-if="user.phone"><a :href="'tel:'+user.phone">{{ user.phone | formatPhone }}</a></p>
              <h4 class="copy-muted-2 font-xs font-bold font-uppercase font-spaced" v-if="user.city">Location</h4>
              <p class="card-contact-info" v-if="user.city">{{ user.city }}, {{ user.state}}</p>
            </div>
          </div>
        </div>
        <div class="card smaller">
          <div class="card-heading-container">
            <h2 class="heading-2">Account Manager</h2>
          </div>
          <div class="card-content-container">
            <div class="card-content-wrap">
              <h3 class="card-contact-name">
                <svg class="icon-person"><use xlink:href="#small-person" /></svg>Sandra Walker
              </h3>
            </div>
            <div class="card-content-wrap">
              <h4 class="copy-muted-2 font-xs font-bold font-uppercase font-spaced">Support</h4>
              <p class="card-contact-info"><a href="mailto:support@goharvey.com">support@goharvey.com</a></p>
              <h4 class="copy-muted-2 font-xs font-bold font-uppercase font-spaced">Phone</h4>
              <p class="card-contact-info"><a href="tel:800-690-9989">800-690-9989</a></p>
              <h4 class="copy-muted-2 font-xs font-bold font-uppercase font-spaced">Available</h4>
              <p class="card-contact-info">Weekdays 9am - 6pm PST</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import DashboardAppointments from './components/DashboardAppointments.vue';
  import { phone } from '../../utils/filters/textformat.js';

  export default {
    name: 'dashboard',
    components: {
      DashboardAppointments
    },
    data() {
      return {
        user: this.Config.user.info
      }
    },
    methods: {
      viewAppointmentPage() {
        // add tracking for Appointments Page view here
      }
    },
    computed: {
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
              name: `${dr.name}, ${typeAbbr}`,
              status: ''
            };
          })[0];
        } else {
          return {
            status: 'Loading your doctor...',
            avatar: '',
            description: '',
            name: ''
          };
        }
      },
      recent_appointments() {
        return this.$root.$data.global.recent_appointments || [];
      },
      upcoming_appointments() {
        return this.$root.$data.global.upcoming_appointments || [];
      },
      viewableIntakeAlert() {
        return !this.$root.$data.global.loadingAppointments &&
               !this.$root.$data.global.appointments.length &&
               this.user.user_type === 'patient';
      },
      appointments() {
        return this.$root.$data.global.appointments;
      }
    },
    mounted() {
      this.$root.$data.global.currentPage = 'dashboard';
      if(this.$root.shouldTrack()) {
        // Add tracking for Dashboard here
      }
    }
  };
</script>
