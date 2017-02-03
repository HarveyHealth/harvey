<template>
    <div class="media">
        <div class="media-left">
            <div class="box">
                <p class="heading">{{ appointment.appointment_at | datetime('dddd') }}</p>
                <p class="title">{{ appointment.appointment_at | datetime('MMM Do') }}</p>
            </div>
            <p v-if="userType != 'patient'"><small>Created at {{ appointment.created_at | datetime('h:mm A - D MMM YYYY') }}</small></p>
        </div>
        <div class="media-content">
            <p class="title">
                <span class="icon"><i class="fa fa-clock-o"></i></span>
                <span>{{ appointment.appointment_at | datetime('h:mm a') }}</span>
            </p>
            <template v-if="userType == 'admin' || userType == 'practitioner'">
                <p class="title is-5">
                    <span class="icon"><i class="fa fa-user"></i></span><span>{{ patient.first_name | capitalize }} {{ patient.last_name | capitalize }}</span>
                </p>
            </template>
            <template v-else>
                <p class="title is-5"><span class="icon"><i class="fa fa-user-md"></i></span><span>With Dr Amanda Frick, ND.</span></p>
            </template>
        </div>
        <div class="media-right">
            <div class="title"><slot></slot></div>
            <p v-if="userType != 'patient'" class="title is-5">
                <a :href="phoneUrl" class="link-inherit-color"><span class="icon"><i class="fa fa-phone"></i></span>{{ patient.phone | phone }}</a>
            </p>
        </div>
    </div>
</template>

<script>
    import {capitalize, phone} from '../../filters/textformat.js';

    export default {
        props: ['appointment', 'userType'],
        data() {
            return {
                patient: {}
            }
        },
        computed: {
            phoneUrl() {
                return 'tel:' + this.patient.phone;
            }
        },
        filters: {
            capitalize,
            phone
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