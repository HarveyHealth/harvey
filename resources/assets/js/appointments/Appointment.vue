<template>
    <div class="appointment">
        <div class="appointment_left">
            <div class="box">
                <p class="appointment_date">{{ localAppointmentTime.format('MMM D [at] h:mm a') }}</p>
            </div>
        </div>
        <div class="appointment_right">
            <template v-if="userType == 'admin' || userType == 'practitioner'">
                <p class="color-greylight">Patient</p>
                <p>{{ capitalize(patient.first_name) }} {{ capitalize(patient.last_name) }}</p>
                <p>
                    <a :href="hyperlink(patient.phone, 'phone')">
                        <span class="icon is-small"><span class="fa fa-phone"></span></span>
                        <span>{{ phone(patient.phone) }}</span>
                    </a>
                </p>
                <p>
                    <a :href="hyperlink(patient.email, 'email')">
                        <span class="icon is-small"><span class="fa fa-envelope"></span></span>
                        <span>{{ patient.email }}</span>
                    </a>
                </p>
            </template>
            <template v-else>
                <p class="appointment_doctor">With Dr {{ appointment.attributes.practitioner_name }}</p>
            </template>
        </div>
        <div class="appointment_left">
            <div v-if="userType == 'patient'" class="title">
                <button class="appointment_button" @click="toggleContact">Reschedule</button>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment-timezone';
    import {capitalize, phone, hyperlink} from '../filters/textformat.js';
    import Contact from '../mixins/Contact';

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
    .appointment {
      border-bottom: 1px solid #E4EAEC;
      overflow: hidden;
      padding: 20px 30px;
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
    .appointment_date {
      opacity: .7;
      font-size: 16px;
    }
    .appointment_doctor {
      font-size: 12px;
      font-weight: 700;
      opacity: .5;
      text-transform: uppercase;
    }
</style>
