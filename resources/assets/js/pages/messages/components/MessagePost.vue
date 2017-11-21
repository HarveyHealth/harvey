<template>
    <div class="container-message" :class="{unread: read}">
        <div class="unread-dot"></div>
        <div class="message-post-details">
          <div class="message-post-avatar">
              <img :src="image" alt="avatar">
          </div>
          <h3 class="heading-2">{{ name }}</h3>
          <h3 class="font-sm copy-muted-2">{{ momentDate }}</h3>
        </div>
        <div class="message-post-body">
          <h2 class="heading-3-expand">{{ subjects }}</h2>
          <p class="message-post-message copy-muted">{{ messages }}</p>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';
    export default {
        props: {
            id: String,
            createdAt: Object,
            header: String,
            image: String,
            message: String,
            name: String,
        },
        name: 'MessagingPost',
        data() {
            return {  };
        },
        computed: {
            messages() {
                let prop = this.$props.message;
                if (prop.split('').length > 45) {
                    let message = prop.split('').splice(0, 42);
                    message[42] === ' ' ? message.push('...') : message.push(' ...');
                    return message.join('');
                }
                return prop;
            },
            momentDate() {
                return moment(`${this.$props.createdAt.date} ${this.$props.createdAt.timezone}`, 'YYYY-MM-DD HH:mm Z').local().format('MMMM Do YYYY, h:mm:ss a z');
            },
            subjects() {
                let prop = this.$props.header;
                if (prop.split('').length > 45) {
                    let header = prop.split('').splice(0, 42);
                    header[42] === ' ' ? header.push('...') : header.push(' ...');
                    return header.join('');
                }
                return prop;
            },
            read() {
                let unread = this.$root.$data.global.unreadMessages;
                return unread.filter(e => e.id == this.$props.id).length > 0;
            }
        }
    };
</script>
