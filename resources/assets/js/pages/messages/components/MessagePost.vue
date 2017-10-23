<template>
    <div class="container-message" :class="{unread: read}">
        <div class="unread-dot"></div>
        <div class="message-post-details">
          <div class="message-post-avatar">
              <img :src="image" alt="avatar">
          </div>
          <h3 class="heading-2">{{ name }}</h3>
          <h3 class="font-sm copy-muted-2">{{ momemtDate }}</h3>
        </div>
        <div class="message-post-body">
          <h2 class="heading-3-expand">{{ subjects }}</h2>
          <p class="message-post-message copy-muted" :class="">{{ messages }}</p>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';
    export default {
        props: ['name', 'day', 'time', 'header', 'message', 'image', 'id', 'timezone'],
        name: 'MessagingPost',
        data() {
            return {  };
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
            momemtDate() {
                moment.tz.add(this.$props.timezone);
                return `${moment(this.$props.day).format("M/D/YYYY")} ${moment(this.$props.time).format("h:mm a")} ${moment.tz(moment.tz.guess()).format('z')}`;
            },
            subjects() {
                if (this.$props.header.split('').length > 50) {
                    let header = this.$props.header.split('').splice(0, 47);
                    header[47] === ' ' ? header.push('...') : header.push(' ...');
                    return header.join('');
                }
                return this.$props.header;
            },
            read() {
                return this.$root.$data.global.unreadMessages.filter(e => e.id == this.$props.id).length > 0;
            }
        }
    };
</script>
