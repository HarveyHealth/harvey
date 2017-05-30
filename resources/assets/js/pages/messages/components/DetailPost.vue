<template>
    <div class="container-message border-message">
        <div class="image-card">
            <img class="image-avatar" src="http://res.cloudinary.com/dzllxh0km/image/upload/v1493268974/smqbmnavbzfsfssjf1hp.jpg" alt="avatar">
        </div>
        <div class="details">
            <h4 class="top-layer">{{ name }}</h4>
            <h4 class="top-layer">{{ day }}</h4>
            <h4 class="top-layer">{{ time }}</h4>
            <h4 class="top-layer">...</h4>
            <h2 class="subject-header">{{ subject }}</h2>
            <h3 class="message-layer">{{ message }}</h3>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['name', 'day', 'time', 'subject', 'message'],
        name: 'MessagingPost',
        components: {

        },
        data() {
            return {

            }
        },
        methods: {

        }
    }
</script>