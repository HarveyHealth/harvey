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
            </div>
            <div class="message-margin">
                <h3 class="message-layer detail-margin">{{ message }}</h3>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment'
    import _ from 'lodash'
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
            axios.put(`${this.$root.$data.apiUrl}/messages/${this.$props.id}/read`)
                .then(response => {
                    this.$root.$data.global.unreadMessages = this.$root.$data.global.unreadMessages.filter(e => e.id !== this.$props.id)
                })
        }
    }
</script>
