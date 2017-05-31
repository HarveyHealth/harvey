<template>
    <div class="container-message border-message">
        <div class="image-card">
            <img class="image-avatar" :src="image" alt="avatar">
        </div>
        <div class="details">
            <h4 class="top-layer">{{ name }}</h4>
            <h4 class="top-layer">{{ day }}</h4>
            <h4 class="top-layer">{{ time }}</h4>
            <h4 class="top-layer">...</h4>
            <h2 class="subject-header">{{ header }}</h2>
            <h3 class="message-layer">{{ message }}</h3>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['name', 'day', 'time', 'header', 'message', 'image'],
        name: 'MessagingPost',
        data() {
            return {

            }
        },
        methods: {

        },
        mounted() {
            // Mark read
            // Load all details
        }
    }
</script>