<template>
    <div>
      <div class="card-heading-container">
        <h2 class="card-header">Upcoming Appointments</h2>
      </div>
      <div class="card-content-container">
        <template v-if="upcomingAppointmentsData && upcomingAppointmentsData.length">
            <div class="appointment-wrapper" v-for="appointment in upcomingAppointmentsData">
                <Appointment
                    :appointment="appointment"
                    :user-type="userType"
                >
                </Appointment>
            </div>
        </template>

        <div v-else class="card-empty-container">
            <p>You have no upcoming appointments.</p>
        </div>

        <div class="card-heading-container">
            <h2 class="card-header">Recent Appointments</h2>
        </div>
        <template v-if="recentAppointmentsData && recentAppointmentsData.length">
            <div class="appointment-wrapper" v-for="appointment in recentAppointmentsData">
                <Appointment
                    :appointment="appointment"
                    :user-type="userType"
                >
                </Appointment>
            </div>
        </template>

          <div v-else class="card-empty-container">
              <p>You have no history.</p>
          </div>
      </div>
    </div>
</template>

<script>
    import Appointment from './Appointment.vue';

    export default {
        props: ['userType', 'recentAppointments', 'upcomingAppointments'],
        components: {
            Appointment,
        },
        computed: {
            upcomingAppointmentsData() {
              return this.upcomingAppointments.data;
            },
            recentAppointmentsData() {
              return this.recentAppointments.data;
            }
        }
    }
</script>
