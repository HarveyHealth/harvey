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
                    :patient-data="getIncludedPatient(upcomingAppointmentsIncluded, appointment)"
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
                    :patient-data="getIncludedPatient(recentAppointmentsIncluded, appointment)"
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
        methods: {
            getIncludedPatient(_included, _appointment) {
                const patientId = _appointment.attributes.patient_id;
                const patientData = {
                  id: patientId,
                };

                // first, get the patient information from the provided patient_id from appointment
                const relatedPatient = _included.map((item) => {
                  if (item.type === 'patients') {
                    patientData.user_id = item.attributes.user_id;
                  }
                });

                // now find the related user
                const relatedUser = _included.map((item) => {

                  // needed since the data types are different
                  if (item.type === 'users' && item.id === patientData.user_id.toString()) {
                    patientData.first_name = item.attributes.first_name;
                    patientData.last_name = item.attributes.last_name;
                    patientData.email = item.attributes.email;
                  }
                });

                return patientData;
            }
        },
        computed: {
            recentAppointmentsData() {
              return this.recentAppointments.data;
            },
            recentAppointmentsIncluded() {
              return this.recentAppointments.included;
            },
            upcomingAppointmentsData() {
              return this.upcomingAppointments.data;
            },
            upcomingAppointmentsIncluded() {
              return this.upcomingAppointments.included;
            }
        },
    }
</script>
