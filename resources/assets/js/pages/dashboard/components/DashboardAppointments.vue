<template>
    <div>
      <div class="card-heading-container">
        <h2 class="heading-2">Appointments</h2>
        <a href="/dashboard#/appointments">View Appointments</a>
      </div>
      <div class="card-content-container">
        <template v-if="upcomingAppointmentsData && upcomingAppointmentsData.length">
            <div class="appointment-wrapper" v-for="appointment in upcomingAppointmentsData">
                <DashboardAppointment
                    :appointment="appointment"
                    :user-type="userType"
                    :patient-data="getIncludedPatient(upcomingAppointmentsIncluded, appointment)"
                >
              </DashboardAppointment>
            </div>
        </template>
        <div v-else class="card-empty-container">
            <p class="copy-muted-2 font-italic">You have no upcoming appointments.</p>
        </div>
      </div>
    </div>
</template>

<script>
import DashboardAppointment from './DashboardAppointment.vue';
import moment from 'moment';

export default {
    props: {
        userType: String, 
        upcomingAppointments: [Object, Array]
    },
    components: {
        DashboardAppointment
    },
    methods: {
        getIncludedPatient(_included, _appointment) {
            const patientId = _appointment.attributes.patient_id;
            const patientData = {
                id: patientId
            };

            return patientData;
        },
        dayOffset(appt, days) {
            const today = moment();
            const apptDate = moment.utc(appt.attributes.appointment_at.date).local();
            return today.diff(apptDate, 'days') <= days;
        }
    },
    computed: {
        upcomingAppointmentsData() {
            if (this.upcomingAppointments.data && this.upcomingAppointments.data.length) {
                return this.upcomingAppointments.data.filter(obj => {
                    return obj.attributes.status !== 'canceled';
                }).reverse();
            } else {
                return [];
            }
        },
        upcomingAppointmentsIncluded() {
            return this.upcomingAppointments.included;
        }
    }
};
</script>
