<template>
  <div class="appointment">
    <div class="appointment_left">
      <template v-if="user_type == 'admin'">
        <p v-text="fullName"></p>
        <p>Dr. {{ appointment.attributes.practitioner_name }}, ND</p>
      </template>
      <template v-else-if="user_type == 'practitioner'">
        <p v-text="fullName"></p>
      </template>
      <template v-else>
        <p>Dr. {{ appointment.attributes.practitioner_name }}, ND</p>
      </template>
    </div>
    <div class="appointment_right">
      <div>
        <router-link
          :to="{ name: 'appointments', params: { appt_id: appointment.id } }"
        >{{ localAppointmentTime }}</router-link>
      </div>
    </div>
  </div>
</template>

<script>
import moment from 'moment-timezone';
import { capitalize, phone, hyperlink } from '../../../utils/filters/textformat.js';
import Contact from '../../../utils/mixins/Contact';

export default {
    mixins: [Contact],
    props: {
        appointment: Object,
        patientData: Object,
        userType: String
    },
    data() {
        return {
            local_timezone: ''
        };
    },
    methods: {
        capitalize,
        phone,
        hyperlink
    },
    computed: {
        localAppointmentTime() {
            return this.$root.addTimezone(moment.utc(this.appointment.attributes.appointment_at.date).local().format('ddd, MMM Do [at] h:mm a'));
        },
        fullName() {
            if (this.patientData.first_name) {
                return `${capitalize(this.patientData.first_name)} ${capitalize(this.patientData.last_name)}`;
            }
            return 'Patient';
        },
        user_type() {
            return this.userType;
        }
    },
    mounted() {
        this.local_timezone = moment.tz.guess();
    }
};
</script>
