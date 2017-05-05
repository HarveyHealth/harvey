<template>
    <div class="main-container">
      <div class="nav-bar">
        <nav class="admin-nav">
          <a class="admin-nav-link dashboard" href="#">
            <svg class="icon icon-person"><use xlink:href="#person" /></svg>
          </a>
        </nav>
        <a href="/logout" class="nav-bar-logout" title="Logout">
          <svg><use xlink:href="#logout" /></svg>
        </a>
        <a href="#" class="nav-bar-account">
          <svg class="harvey-mark"><use xlink:href="#harvey-mark" /></svg>
        </a>
      </nav>
      <a href="#" class="nav-bar-account">
        <svg class="harvey-mark"><use xlink:href="#harvey-mark" /></svg>
      </a>
    </div>
    <div class="main-content">
      <div class="main-header">
        <div class="container">
          <h1 class="title header-xlarge">{{ dashboardTitle }}
          <a href="#" class="button main-action disabled">New Appointment</a></h1>
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
              <h3 class="card-contact-name"><svg class="icon-person"><use xlink:href="#small-person" /></svg>{{ displayName }}</h3>
            </div>
            <div v-if="upcoming_appointments.length > 0" class="card-content-wrap">
              <h4 class="card-contact-sublabel">Doctor</h4>
              <p class="card-contact-info">{{ upcoming_appointments[0].attributes.practitioner_name }}</p>
            </div>
            <div class="card-content-wrap">
              <h4 class="card-contact-sublabel">Email</h4>
              <p class="card-contact-info">{{ user.attributes.email }}</p>
              <h4 class="card-contact-sublabel">Zip</h4>
              <p class="card-contact-info">{{ user.attributes.zip }}</p>
              <h4 class="card-contact-sublabel">Phone</h4>
              <p class="card-contact-info">{{ user.attributes.phone }}</p>
              <h4 class="card-contact-sublabel">ID</h4>
              <p class="card-contact-info">#100{{ user.id }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import Appointments from '../../appointments/Appointments.vue';
  import {capitalize, phone, hyperlink} from '../../filters/textformat.js';
  import Contact from '../../mixins/Contact';

  export default {
    name: 'dashboard',
    data() {
      return {
        'upcoming_appointments': [],
        'recent_appointments': []
      };
    },
    props: ['user', 'patient'],
    components: {
      Appointments
    },
    methods: {
      viewAppointmentPage() {
        this.$eventHub.$emit('mixpanel', "View New Appointment Page");
      }
    },
    computed: {
      displayName() {
          if(this.user.attributes === undefined) {
              return 'Harvey Client';
          } else {
              return this.user.attributes.first_name;
          }
      },
      dashboardTitle() {
        if (this.userType === 'admin') {
          return 'Admin Dashboard';
        } else {
          return 'Your Dashboard';
        }
      },
      userType() {
        return this.user.attributes ? this.user.attributes.user_type : null;
      },
    },
    mounted() {
      this.$http.get(this.$root.apiUrl + '/appointments?filter=upcoming')
        .then((response) => {
          this.upcoming_appointments = response.data.data;
        });
      this.$http.get(this.$root.apiUrl + '/appointments?filter=recent')
        .then((response) => {
          this.recent_appointments = response.data.data;
        });
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
