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
            <div class="message-margin"><h2 class="subject-header">{{ subjects }}</h2></div>
            <h3 class="message-layer">{{ messages }}</h3>
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
        computed: {
            messages() {
                if (this.$props.message.split('').length > 50) {
                    let message = this.$props.message.split('').splice(0, 47);
                    message[47] === ' ' ? message.push('...') : message.push(' ...');
                    return message.join('');
                }
                return this.$props.message;
            },
            subjects() {
                if (this.$props.header.split('').length > 50) {
                    let header = this.$props.header.split('').splice(0, 47);
                    header[47] === ' ' ? header.push('...') : header.push(' ...');
                    return header.join('');
                }
                return this.$props.header;
            }
        }
    }
</script>