<template>
    <div class="panel">
        <h2 class="panel-heading">Upcoming Appointments</h2>
        <template v-if="upcoming_appointments.length">
            <div v-for="appointment in upcoming_appointments" class="panel-block">
                <Appointment
                    :appointment="appointment"
                    :user-type="userType"
                >
                    <button class="button" @click="toggleContact">Reschedule</button>
                </Appointment>
            </div>
        </template>
        <div v-else class="panel-block">
            <p>There are no upcoming appointments.</p>
        </div>

        <template v-if="recent_appointments.length">
            <h2 class="panel-heading">Recent Appointments</h2>
            <div v-for="appointment in recent_appointments" class="panel-block">
                <Appointment
                    :appointment="appointment"
                    :user-type="userType"
                >
                    <!-- <button class="button is-disabled">Pending</button> -->
                </Appointment>
            </div>
        </template>
    </div>
</template>

<script>
    import Appointment from './Appointment.vue';
    import Contact from '../mixins/Contact';

    export default {
        mixins: [Contact],
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