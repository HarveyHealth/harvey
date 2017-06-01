<template>
    <div class="container-message" :class="{unread: !read}">
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
            <div class="message-margin"><h2 class="subject-header">{{ header }}</h2></div>
            <div><h3 class="message-layer">{{ message }}</h3></div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment'
    export default {
        props: ['name', 'day', 'time', 'header', 'message', 'image', 'read'],
        name: 'MessagingPost',
        data() {
            return {
                moment: moment
            }
        },
        methods: {

        }
    }
</script>