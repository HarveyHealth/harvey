<template>
    <div style="background-color: white; height: 200px;">
        <div style="width: 6%; height: 6%; float: left;">
            <img style="border-radius: 50%; margin: 85% 20%; float: left;" src="http://res.cloudinary.com/dzllxh0km/image/upload/v1493268974/smqbmnavbzfsfssjf1hp.jpg" alt="avatar">
        </div>
        <div style="float: left; margin: 70px; 50px; width: 80%;">
            <h4 style="float: left; margin: 0 1%;">{{ name }}</h4>
            <h4 style="float: left; margin: 0 1%;">{{ day }}</h4>
            <h4 style="float: left; margin: 0 1%;">{{ time }}</h4>
            <h4 style="float: left; margin: 0 1%;">...</h4>
            <h2 style="float: left; margin: 6px 100% 0 0;">{{ subject }}</h2>
            <h3 style="float: left; margin-top: 6px;">{{ message }}</h3>
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

</style>