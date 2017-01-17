<template>
    <div class="container">
        <nav class="nav">
            <div class="nav-right">
                <span class="nav-item">
                    <a href="" class="button is-primary">
                        <span class="icon"><i class="fa fa-user-plus"></i></span>
                        <span>New Appointment</span>
                    </a>
                </span>
                <span class="nav-item">
                    <a href="" class="button">
                        <span class="icon"><i class="fa fa-phone"></i></span>
                        <span>Contact Us</span>
                    </a>
                </span>
            </div>
        </nav>
        <div class="section columns">
            <div class="column is-8">
                <Appointments></Appointments>
            </div>
            <div class="column is-4">
                <Tests></Tests>
            </div>
        </div>
    </div>
</template>

<script>
    import Appointments from './Appointments.vue';
    import Tests from './Tests.vue';

    export default {
        data() {
            return {
            }
        },
        components: {
            Appointments,
            Tests
        },
        mounted() {
            console.log('launch dashboard')
        }
    }
</script>