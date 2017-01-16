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
                <appointments></appointments>
            </div>
            <div class="column is-4">
                <tests></tests>
            </div>
        </div>
    </div>
</template>

<script>
    import appointments from './Appointments.vue';
    import tests from './Tests.vue';

    export default {
        data() {
            return {
            }
        },
        components: {
            appointments,
            tests
        },
        mounted() {
            console.log('launch dashboard')
        }
    }
</script>