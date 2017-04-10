<template>
    <div class="main-container">
      <div class="nav-bar">
        =
      </div>
      <div class="main-content">
        <div class="main-header">
          <h1 class="title">Dashboard</h1>
        </div>
        <div class="card-wrapper">
          <div class="card">
              <Appointments :user-type="userType"></Appointments>
          </div>
          <div class="card smaller">
              Contact Info Goes Here
          </div>
        </div>
      </div>
    </div>
</template>

<script>
    import Appointments from '../../appointments/Appointments.vue';

    export default {
        name: 'dashboard',
        props: ['user'],
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
