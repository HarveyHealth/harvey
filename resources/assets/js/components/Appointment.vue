<template>
    <div class="media">
        <div class="media-left">
            <div class="box">
                <p class="heading">{{ appointment.appointment_at | datetime('dddd') }}</p>
                <p class="title">{{ appointment.appointment_at | datetime('MMM Do') }}</p>
            </div>
        </div>
        <div class="media-content">
            <p class="title">
                <span class="icon"><i class="fa fa-clock-o"></i></span>
                <span>{{ appointment.appointment_at | datetime('h:mm a') }}</span>
            </p>
            <p>With Dr Amanda Frick, ND.</p>
        </div>
        <div class="media-right">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['appointment']
    }
</script>
