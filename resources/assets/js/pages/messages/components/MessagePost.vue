<template>
    <div style="border-left: 6px solid #82BEF2; background-color: white; height: 200px;">
        <div style="width: 50px; height: 50px; float: left;">
            <img style="border-radius: 50%; margin: 150% 60px; float: left;" src="http://res.cloudinary.com/dzllxh0km/image/upload/v1493268974/smqbmnavbzfsfssjf1hp.jpg" alt="avatar">
        </div>
        <div style="float: left; margin: 70px; 50px;">
            <h4 style="float: left; margin: 0 20px;">{{ name }}</h4>
            <h4 style="float: left; margin: 0 20px;">{{ day }}</h4>
            <h4 style="float: left; margin: 0 20px;">{{ time }}</h4>
            <h4 style="float: left; margin: 0 20px;">...</h4>
        </div>
        <div style="float: left; margin: 100px -325px;">
            <h2>{{ subject }}</h2>
        </div>
        <div  style="float: left; margin: 140px -325px;">
            <h3>{{ message }}</h3>
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