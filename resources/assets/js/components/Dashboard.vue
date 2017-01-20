<template>
    <div class="container">
        <nav class="nav">
            <div class="nav-right">
                <span class="nav-item">
                    <router-link tag="a" to="/new-appointment" class="button is-primary">
                        <span class="icon"><i class="fa fa-user-plus"></i></span>
                        <span @click="viewAppointmentPage">New Appointment</span>
                    </router-link>
                </span>
                <span class="nav-item">
                    <a href="" class="button">
                        <span class="icon"><i class="fa fa-phone"></i></span>
                        <span>Contact Us</span>
                    </a>
                </span>
            </div>
        </nav>
        <div class="section">
            <h1 class="title">Dashboard</h1>
            <div class="columns">
                <div class="column is-8">
                    <Appointments></Appointments>
                </div>
                <div class="column is-4">
                    <Tests></Tests>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Appointments from './Appointments.vue';
    import Tests from './Tests.vue';

    export default {
        name: 'dashboard',
        data() {
            return {
            }
        },
        components: {
            Appointments,
            Tests
        },
        methods: {
            viewAppointmentPage() {
                mixpanel.track("View New Appointment Page");
            }
        }
    }
</script>