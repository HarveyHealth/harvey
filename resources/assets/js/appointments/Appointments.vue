<template>
    <div>
      <div class="card-heading-container">
        <h2 class="card-header">Upcoming Appointments</h2>
      </div>
      <div class="card-content-container">
        <template v-if="upcomingAppointments.length">
            <div v-for="appointment in upcomingAppointments">
                <Appointment
                    :appointment="appointment"
                    :user-type="userType"
                >
                </Appointment>
            </div>
        </template>

        <div v-else>
            <p>There are no upcoming appointments.</p>
        </div>

        <div class="card-heading-container">
            <h2 class="card-header">Recent Appointments</h2>
        </div>
        <template v-if="recentAppointments.length">
            <div class="appointment-wrapper" v-for="appointment in recentAppointments">
                <Appointment
                    :appointment="appointment"
                    :user-type="userType"
                >
                </Appointment>
            </div>
        </template>

          <div v-else>
              <p>There are no recent appointments.</p>
          </div>
      </div>
    </div>
</template>

<script>
    import Appointment from './Appointment.vue';

    export default {
        props: ['userType', 'recentAppointments', 'upcomingAppointments'],
        components: {
            Appointment
        },
    }
</script>
