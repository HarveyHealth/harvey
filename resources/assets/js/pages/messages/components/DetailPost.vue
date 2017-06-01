<template>
    <div class="container-message border-message">
        <div class="image-card">
            <img class="image-avatar" :src="image" alt="avatar">
        </div>
        <div class="details">
            <div class="top-layer-margin">
                <h4 class="top-layer top-layer-margin">{{ name }}</h4>
                <h4 class="top-layer">{{ moment(day).format("MMM Do YYYY") }}</h4>
                <h4 class="top-layer">{{ moment.utc(time).local().format("h:mm a") }}</h4>
                <h4 class="top-layer">...</h4>
            </div>
            <div class="message-margin"><h3 class="message-layer">{{ message }}</h3></div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment'
    export default {
        props: ['name', 'day', 'time', 'header', 'message', 'image', 'id'],
        name: 'MessagingPost',
        data() {
            return {
                moment: moment
            }
        },
        methods: {

        },
        mounted() {
            axios.put(`/api/v1/messages/${this.$props.id}/read`)
        }
    }
</script>