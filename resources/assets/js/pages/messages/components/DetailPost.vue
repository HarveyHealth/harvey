<template>
    <div>
      <div class="message-post-details">
        <div class="message-post-avatar">
            <img :src="image" alt="avatar">
        </div>
        <h3 class="message-post-name heading-2" :class="{highlight: yourId == userId ? 'highlight' : ''}">{{ name }}</h3>
        <h3 class="message-post-time copy-muted-2 font-sm font-thin">{{ momentDate }}</h3>
      </div>
      <p class="message-post-body" :class="{highlight: yourId == userId ? 'highlight' : ''}">{{ message }}</p>
    </div>
</template>

<script>
    import moment from 'moment';
    export default {
        props: {
            name: String,
            day: String,
            time: String,
            header: String,
            message: String,
            image: String,
            id: String,
            userId: String,
            timezone: String,
            yourId: String
        },
        name: 'DetailPost',
        data() {
            return {  };
        },
        computed: {
            momentDate() {
                return `${moment(this.$props.day).format("M/D/YYYY")} ${moment(this.$props.time).format("h:mm a")} ${this.$root.$data.timezoneAbbr}`;
            }
        },
        mounted() {
            if (this.$root.$data.global.user.id == this.$props.userId) {
                axios.put(`${this.$root.$data.apiUrl}/messages/${this.$props.id}/read`)
                    .then(() => {
                        this.$root.$data.global.unreadMessages = this.$root.$data.global.unreadMessages.filter(e => e.id !== this.$props.id);
                    });
            }
        }
    };
</script>
