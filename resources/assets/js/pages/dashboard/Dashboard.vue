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
          <h1 class="title header-xlarge">Your Dashboard</h1>
        </div>
        <div class="card-wrapper">
          <div class="card">
              <Appointments :user-type="userType"></Appointments>
          </div>
          <div class="card smaller">
            <div class="card-heading-container">
              <h2 class="card-header">Contact Information</h2>
            </div>
            <div class="card-content-container">
              <div class="card-content-wrap">
                <h3 class="card-contact-name">
                  <svg class="icon-person"><use xlink:href="#small-person" /></svg>
                  First Name</h3>
              </div>
              <div class="card-content-wrap">
                <h4 class="card-contact-sublabel">Doctor</h4>
                <p class="card-contact-info">Doctor Name</p>
              </div>
              <div class="card-content-wrap">
                <h4 class="card-contact-sublabel">Email</h4>
                <p class="card-contact-info">email@address.com</p>
                <h4 class="card-contact-sublabel">Zip</h4>
                <p class="card-contact-info">111111</p>
                <h4 class="card-contact-sublabel">Phone</h4>
                <p class="card-contact-info">(111) 111-1111</p>
                <h4 class="card-contact-sublabel">ID</h4>
                <p class="card-contact-info">#123456</p>
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
            userType() {
                return this.user.user_type;
            }
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
