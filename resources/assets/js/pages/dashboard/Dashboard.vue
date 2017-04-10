<template>
    <div class="container">
        <nav class="nav">
            <span class="nav-item">
                <router-link tag="a" to="/new-appointment" class="button is-primary">
                    <span class="icon"><i class="fa fa-user-plus"></i></span>
                    <span @click="viewAppointmentPage">New Appointment</span>
                </router-link>
            </span>
            <span class="nav-item">
                <a class="button" @click="toggleContact">
                    <span class="icon"><i class="fa fa-phone"></i></span>
                    <span>Contact Us</span>
                </a>
            </span>
        </nav>
        <div class="section">
            <h1 class="title">Dashboard</h1>
            <div class="columns">
                <div class="column is-7">
                    <Appointments :user-type="userType"></Appointments>
                </div>
                <div class="column is-5">
                    <Tests :user-type="userType"></Tests>
                </div>
            </div>
        </div>
        <contact></contact>
    </div>
</template>

<script>
    import Appointments from '../../appointments/Appointments.vue';
    import Tests from '../../tests/Tests.vue';
    import Contact from '../../mixins/Contact';

    export default {
        name: 'dashboard',
        mixins: [Contact],
        props: ['user'],
        components: {
            Appointments,
            Tests
        },
        methods: {
            viewAppointmentPage() {
                this.$eventHub.$emit('mixpanel', "View New Appointment Page");
            }
        },
        computed: {
            userType() {
                return this.user.user_type;
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>
