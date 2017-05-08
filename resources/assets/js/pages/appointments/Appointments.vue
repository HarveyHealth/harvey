<template>
  <div class="main-container">

    <div class="nav-bar">
      <nav class="admin-nav">
        <a class="admin-nav-link dashboard" href="#">
          <svg class="icon icon-person"><use xlink:href="#person" /></svg>
        </a>
        <a href="/logout" class="nav-bar-logout" title="Logout">
          <svg><use xlink:href="#logout"/></svg>
        </a>
        <a href="#" class="nav-bar-account">
          <svg class="harvey-mark"><use xlink:href="#harvey-mark" /></svg>
        </a>
      </nav>
    </div>

    <div class="main-content">
      <div class="main-header">
        <div class="container">
          <h1 class="title header-xlarge">
            Appointments
            <a href="/schedule" class="button main-action">New Appointment</a>
          </h1>
        </div>
      </div>
      <AppointmentsWrapper :comp="'TableData'" :transform="test" />
    </div>
  </div>
</template>

<script>
  // import Flyout from './_components/Flyout.vue';
  import AppointmentsWrapper from './_components/AppointmentsWrapper.vue';
  import { capitalize, phone, hyperlink } from '../../filters/textformat.js';
  import Contact from '../../mixins/Contact';

  export default {
    name: 'appointments',
    data() {
      return {
        userType: Laravel.user.userType
      };
    },
    props: ['user', 'patient'],
    components: {
      AppointmentsWrapper,
    },
    methods: {
      test(thing) {
        return [{'col_a': 'foo', 'col_b': 'bar'}];
      }
    },
    computed: {
    },
    created() {
    },
    mounted() {
    }
  }
</script>
