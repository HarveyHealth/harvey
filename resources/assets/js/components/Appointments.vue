<template>
    <div class="panel">
        <h2 class="panel-heading">Upcoming Appointments</h2>
        <template v-if="upcoming_appointments.length">
            <div v-for="appointment in upcoming_appointments" class="panel-block">
                <appointment
                    :appointment="appointment"
                >
                    <button class="button">Reschedule</button>
                </appointment>
            </div>
        </template>
        <div v-else class="panel-block">
            <p>There are no upcoming appointments.</p>
        </div>

        <h2 class="panel-heading">Recent Appointments</h2>
        <template v-if="recent_appointments.length">
            <div v-for="appointment in recent_appointments" class="panel-block">
                <appointment
                    :appointment="appointment"
                >
                    <button class="button">Read Notes</button>
                </appointment>
            </div>
        </template>
        <div v-else class="panel-block">
            <p>There are no recent appointments.</p>
        </div>
    </div>
</template>

<script>
    import appointment from './Appointment.vue';

    export default {
        data() {
            return {
                upcoming_appointments: [],
                recent_appointments: []
            }
        },
        components: {
            appointment
        },
        mounted() {
            this.$http.get('/api/dashboard').then(response => {
                this.upcoming_appointments = response.data.upcoming_appointments;
                this.recent_appointments = response.data.recent_appointments;
            });
        }
    }
</script>