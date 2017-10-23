<template>
  <div class="main-container" :class="userClass">
    <div class="main-content">
      <div class="main-header">
        <div class="container container-backoffice">
          <h1 class="heading-1">{{ dashboardTitle }}</h1>
        </div>
      </div>
      <div class="card-wrapper">
        <div class="card card-panel">
          <DashboardAppointments :user-type="userType" :upcoming-appointments="upcoming_appointments" />
        </div>
        <div class="card card-panel" v-if="userType === 'patient'">
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
            <div class="card-content-wrap" v-if="patientName">
              <h3 class="card-contact-name">
                <svg class="icon-person"><use xlink:href="#small-person" /></svg>{{ displayName }}
              </h3>
            </div>
            <div class="card-content-wrap">
              <h4 class="copy-muted-2 font-xs font-bold font-uppercase font-spaced" v-if="displayName">Name</h4>
              <p class="card-contact-info" v-if="displayName">{{ displayName }}</p>
              <h4 class="copy-muted-2 font-xs font-bold font-uppercase font-spaced" v-if="email">Email</h4>
              <p class="card-contact-info" v-if="email"><a :href="'mailto:'+email">{{ email }}</a></p>
              <h4 class="copy-muted-2 font-xs font-bold font-uppercase font-spaced" v-if="phone">Phone</h4>
              <p class="card-contact-info" v-if="phone"><a :href="'tel:'+phone">{{ phone }}</a></p>
              <h4 class="copy-muted-2 font-xs font-bold font-uppercase font-spaced" v-if="cityState">Location</h4>
              <p class="card-contact-info" v-if="cityState">{{ cityState }}</p>
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
    data() {
      return {
        patientName: Laravel.user.fullName, // because it's already there
        flag: false,
        user: this.$root.$data.global.user
      };
    },
    components: {
      DashboardAppointments
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
          return 'Dashboard';
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
      userClass() {
        return {
          [`is-${this.userType}`]: true
        };
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
      cityState() {
        return this.user.attributes ? `${this.user.attributes.city}, ${this.user.attributes.state}` : '';
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
      if(this.$root.shouldTrack()) {
        // Add tracking for Dashboard here
      }
    }
  };
</script>
