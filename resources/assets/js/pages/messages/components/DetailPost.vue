<template>
    <div>
      <div class="message-post-details">
        <div class="message-post-avatar">
            <img :src="image" alt="avatar">
        </div>
        <h3 class="message-post-name heading-secondary">{{ name }}</h3>
        <h3 class="message-post-time copy-muted-2 font-sm font-thin">{{ moment(day).format("M/D/YYYY") }}, {{ moment.tz(time).format("h:mm a") }} {{ moment.tz(moment.tz.guess()).format('z') }}</h3>
      </div>
      <div class="message-post-body">
        <p class="message-post-message">{{ message }}</p>
      </div>
    </div>
</template>

<script>
    import moment from 'moment'
    import _ from 'lodash'
    export default {
        props: ['name', 'day', 'time', 'header', 'message', 'image', 'id', 'userId', 'timezone'],
        name: 'MessagingPost',
        data() {
            return {
                moment: moment
            }
        },
        mounted() {
            if (this.$root.$data.global.user.id == this.$props.userId) {
                axios.put(`${this.$root.$data.apiUrl}/messages/${this.$props.id}/read`)
                    .then(response => {
                        this.$root.$data.global.unreadMessages = this.$root.$data.global.unreadMessages.filter(e => e.id !== this.$props.id)
                    })
            }
        }
    }
</script>
