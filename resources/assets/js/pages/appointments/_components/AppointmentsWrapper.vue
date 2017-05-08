<template>
  <component :is="comp"></component>
</template>

<script>
    export default {
        data() {
            return {
                appointments: [],
            }
        },
        props: ['userType', 'comp'],
        methods: {
            getIncludedPatient(_included, _appointment) {
                const patientId = _appointment.attributes.patient_id;
                const patientData = {
                    id: patientId,
                };

                // first, get the patient information from the provided patient_id from appointment
                const relatedPatient = _included.map((item) => {
                    if (item.type === 'patients' && item.id === patientData.id.toString()) {
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
                        patientData.phone = item.attributes.phone;
                    }
                });

                return patientData;
            }
        },
        computed: {
            appointmentsData() {
                return this.appointments.data;
            },
            appointmentsInclude() {
                return this.appointments.included;
            },
            appointmentPatients() {
                return this.appointments.data.map(appt => {
                  return this.getIncludedPatient(this.appointments.included, appt);
                })
            }
        },
        created() {
          axios.get('/api/v1/appointments?include=patient.user').then(response => {
            this.appointments = response.data;
          })
        },
        mounted() {
          setTimeout(() => {
            this.appointmentsData.forEach(appt => {
              console.log(appt);
              console.log(this.getIncludedPatient(this.appointmentsInclude, appt));
            })
          }, 3000);
        }
    }
</script>
