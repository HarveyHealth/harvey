<template>
    <div>
      <div class="card-heading-container">
        <h2 class="card-header">Upcoming Appointments</h2>
      </div>
      <div class="card-content-container">
        <template v-if="upcoming_appointments.length">
            <div v-for="appointment in upcoming_appointments">
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

        <template v-if="recent_appointments.length">
            <div class="appointment-wrapper" v-for="appointment in recent_appointments">
                <Appointment
                    :appointment="appointment"
                    :user-type="userType"
                >
                </Appointment>
            </div>
        </template>
      </div>
    </div>
</template>

<script>
    import Appointment from './Appointment.vue';

    export default {
        props: ['userType'],
        data() {
            return {
                upcoming_appointments: [],
                recent_appointments: []
            }
        },
        components: {
            Appointment
        },
        mounted() {
            this.$http.get('/api/dashboard').then(response => {
                this.upcoming_appointments = response.data.upcoming_appointments;
                this.recent_appointments = response.data.recent_appointments;
            });
        }
    }
</script>
