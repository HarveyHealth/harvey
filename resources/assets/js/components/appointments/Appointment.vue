<template>
    <div class="media">
        <div class="media-left">
            <div class="box">
                <p class="heading">{{ appointment.appointment_at | datetime('dddd') }}</p>
                <p class="title is-4">{{ appointment.appointment_at | datetime('MMM D') }}</p>
                <p class="subtitle">at {{ appointment.appointment_at | datetime('h:mm a') }}</p>
            </div>
            <p v-if="userType != 'patient'" class="color-greylight"><small>created at {{ appointment.created_at | datetime('h:mm A, D MMM YYYY') }}</small></p>
        </div>
        <div class="media-content">
            <template v-if="userType == 'admin' || userType == 'practitioner'">
                <p class="color-greylight">Patient</p>
                <p class="title is-4">{{ capitalize(patient.first_name) }} {{ capitalize(patient.last_name) }}</p>
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
                <p class="title is-5"><span class="icon"><i class="fa fa-user-md"></i></span><span>With Dr Amanda Frick, ND.</span></p>
            </template>
        </div>
        <div class="media-right">
            <div v-if="userType == 'patient'" class="title">
                <button class="button" @click="toggleContact">Reschedule</button>
            </div>
            <p v-else class="title is-5">
                <button class="button">
                    <span class="icon"><span class="fa fa-cloud-upload"></span></span>
                    <span>Upload Notes</span>
                </button>
            </p>
        </div>
    </div>
</template>

<script>
    import {capitalize, phone, hyperlink} from '../../filters/textformat.js';
    import Contact from '../../mixins/Contact';

    export default {
        mixins: [Contact],
        props: ['appointment', 'userType'],
        data() {
            return {
                patient: {}
            }
        },
        methods: {
            capitalize,
            phone,
            hyperlink
        },
        mounted() {
            if (this.userType !== 'patient') {
                this.$http.get(this.$root.apiUrl + '/users/' + this.appointment.patient_user_id)
                    .then((response) => {
                        this.patient = response.data.data;
                    })
            }
        }
    }
</script>

<style lang="sass" scoped>
    .box:not(:last-child) {
        margin-bottom: 0;
    }
    .media-right {
        text-align: right;
    }
</style>