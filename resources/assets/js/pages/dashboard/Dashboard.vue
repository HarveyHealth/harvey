<template>
  <div class="main-container">

    <UserNav />

    <div class="main-content">
      <div class="main-header">
        <div class="container">
          <h1 class="title header-xlarge">{{ dashboardTitle }}</h1>
        </div>
      </div>
      <div class="card-wrapper">
        <div class="card">
          <DashboardAppointments :user-type="userType"
                        :recent-appointments="recent_appointments"
                        :upcoming-appointments="upcoming_appointments"></DashboardAppointments>
        </div>
        <div class="card smaller">
          <div class="card-heading-container">
            <h2 class="card-header">Your Info</h2>
            <!-- <a href="/dashboard#/appointments">Edit Info</a> -->
          </div>
          <div class="card-content-container">
            <div class="card-content-wrap" v-if="patientName">
              <h3 class="card-contact-name">
                <svg class="icon-person"><use xlink:href="#small-person" /></svg>{{ patientName }}
              </h3>
            </div>
            <div v-if="upcoming_appointments.length > 0" class="card-content-wrap">
              <h4 class="card-contact-sublabel">Doctor</h4>
              <p class="card-contact-info">{{ upcoming_appointments[0].attributes.practitioner_name }}</p>
            </div>
            <div class="card-content-wrap">
              <h4 class="card-contact-sublabel" v-if="email">Email</h4>
              <p class="card-contact-info" v-if="email">{{ email }}</p>
              <h4 class="card-contact-sublabel" v-if="zip">Zip</h4>
              <p class="card-contact-info" v-if="zip">{{ zip }}</p>
              <h4 class="card-contact-sublabel" v-if="phone">Phone</h4>
              <p class="card-contact-info" v-if="phone"><a href="tel">{{ phone }}</a></p>
              <h4 class="card-contact-sublabel" v-if="user_id">ID</h4>
              <p class="card-contact-info" v-if="user_id">#{{ user_id }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import DashboardAppointments from './components/DashboardAppointments.vue';
  import UserNav from '../../commons/UserNav.vue';

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
      UserNav,
    },
    methods: {
      viewAppointmentPage() {
        this.$eventHub.$emit('mixpanel', "View New Appointment Page");
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
      patientName() {
        
      },
      phone() {
        return this.user.attributes ? phone(this.user.attributes.phone) : '';
      },
      recent_appointments() {
        return this.$root.$data.global.recent_appointments || [];
      },
      upcoming_appointments() {
        return this.$root.$data.global.upcoming_appointments || [];
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
    // created() {
    //   axios.get('/api/v1/appointments?filter=upcoming&include=patient.user')
    //     .then((response) => {
    //       this.upcoming_appointments = response.data;
    //     });
    //   axios.get('/api/v1/appointments?filter=recent&include=patient.user')
    //     .then((response) => {
    //       this.recent_appointments = response.data;
    //     });
    // },
    beforeMount() {
      let flag = localStorage.getItem('signed up');
      if (flag) {
        localStorage.removeItem('signed up');
      }
    },
    mounted() {
      if (localStorage.getItem('signed up')) return null;

    }

  }
</script>

<style lang="scss" scoped>
  .icon-person {
    height: 30px;
    margin-right: 15px;
    width: 30px;
    vertical-align: top;
  }
</style>
