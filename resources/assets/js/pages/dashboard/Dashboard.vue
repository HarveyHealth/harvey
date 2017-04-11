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
              <h2 class="card-header">Contact Info goes here</h2>
            </div>
            <div class="card-content-container">
              info goes here
            </div>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
    import Appointments from '../../appointments/Appointments.vue';

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

</style>
