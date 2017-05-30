<template>
    <div class="container-message">
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

<style>
    .unread {
        border-left: 6px solid #82BEF2; 
    }
    .container-message {
        background-color: white; 
        height: 200px;
        border-left: none;
    }
    .top-layer {
        float: left; 
        margin: 0 1%;
    }
    .subject-header {
        float: left; 
        margin: 6px 100% 0 0;
    }
    .message-layer {
        float: left; 
        margin-top: 6px;
    }
    .details {
        float: left; 
        margin:6% 3%; 
        width: 80%;
    }
    .image-card {
        width: 6%; 
        height: 6%; 
        float: left;
        margin: 2%;
    }
    .image-avatar {
        border-radius: 50%; 
        margin: 85% 20%; 
        float: left;
    }
</style>