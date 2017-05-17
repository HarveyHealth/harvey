<template>
    <router-link to="/new-appointments" class="appointment-row" alt="Appointment Details">
        <div class="appointment">
            <div class="appointment_left">
                <template v-if="userType == 'admin' || userType == 'practitioner'">
                    <span class="appointment_patient">Patient Name {{ capitalize(patient.first_name) }} {{ capitalize(patient.last_name) }}</span>
                </template>
                <template v-else>
                    <span class="appointment_doctor">Dr. {{ appointment.attributes.practitioner_name }}</span>
                </template>
            </div>
            <div class="appointment_right">
                <div class="box">
                    <span class="appointment_date">{{ localAppointmentTime.format('ddd, MMM Do [at] h:mma') }}</span>
                </div>
            </div>
            <div class="appointment_right">
                <div v-if="userType == 'patient'" class="title">
                    <button class="appointment_button" @click="toggleContact">Reschedule</button>
                </div>
            </div>
        </div>
    </router-link>
</template>

<script>
    import moment from 'moment-timezone';
    import {capitalize, phone, hyperlink} from '../../../utils/filters/textformat';
    import Contact from '../../../utils/mixins/Contact';

    export default {
        mixins: [Contact],
        props: ['appointment', 'userType'],
        data() {
            return {
                patient: {},
                local_timezone: 'America/Los_Angeles'
            }
        },
        methods: {
            capitalize,
            phone,
            hyperlink
        },
        computed: {
            localAppointmentTime() {
                let m = moment.utc(this.appointment.attributes.appointment_at.date, "YYYY-MM-DD h:i:s");
                return moment(m).tz(this.local_timezone);
            }
        },
        mounted() {
            this.local_timezone = moment.tz.guess();

//            if (this.userType !== 'patient') {
//                this.$http.get(this.$root.apiUrl + '/users/' + this.appointment.patient_user_id)
//                    .then((response) => {
//                        this.patient = response.data.data;
//                    })
//            }
        }
    }
</script>

<style lang="scss" scoped>
  .appointment-wrapper:last-of-type .appointment {
    border-bottom: none;
  }

    .appointment-row{

        .appointment {
          border-bottom: 1px solid #E4EAEC;
          overflow: hidden;
          padding: 20px 30px;
        }

        &:hover{
            cursor: pointer;
            .appointment {
               opacity: .7; 
            }
        }
    }
    .appointment_left {
      float: left;

      p {
        margin-bottom: 0;
      }
    }
    .appointment_right {
      float: right;
      p {
        margin-bottom: 0;
      }
    }
    .appointment_date, .appointment_patient, .appointment_doctor {
      font-weight: 300;
      letter-spacing: 1px;
      font-size: 16px;
    }
</style>
