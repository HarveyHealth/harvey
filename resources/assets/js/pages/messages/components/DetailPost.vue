<template>
    <div class="container-detail-message">
        <div class="image-card">
            <img class="image-avatar" :src="image" alt="avatar">
        </div>
        <div class="details">
            <div class="top-layer-margin">
                <h4 class="top-layer top-layer-margin">{{ name }}</h4>
                <h4 class="top-layer">{{ moment(day).format("MMM Do YYYY") }}</h4>
                <h4 class="top-layer">{{ moment.utc(time).local().format("h:mm a") }}</h4>
                <h4 class="top-layer"><i class="fa fa-ellipsis-h"></i></h4>
            </div>
            <div class="message-margin">
                <h3 class="message-layer">{{ message }}</h3>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment'
    export default {
        props: ['name', 'day', 'time', 'header', 'message', 'image', 'id', 'userId'],
        name: 'MessagingPost',
        data() {
            return {
                moment: moment
            }
        },
        methods: {

        },
        mounted() {
            if (this.$props.userId == this.$root.$data.global.user.id) {
                axios.put(`${this.$root.$data.apiUrl}/messages/${this.$props.id}/read`)
            }
        }
    }
</script>
