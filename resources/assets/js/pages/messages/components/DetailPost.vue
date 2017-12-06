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
      <i v-if="$root.$data.global.permissions !== 'patient'" class="fa fa-trash-o" @click="deleteMessages"></i>
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
            userId: String,
            yourId: String,
            threadId: String,
        },
        name: 'DetailPost',
        data() {
            return {  };
        },
        methods: {
            deleteMessages() {
                axios.delete(`${this.$root.$data.apiUrl}/messages/${this.$props.id}`)
                .then(() => {
                    delete this.$root.$data.global.detailMessages[threadId][this.$props.id];
                    this.$root.$data.global.messages = Object.values(this.$root.$data.global.detailMessages)
                    .map(e => e[e.length - 1])
                    .sort((a, b) => b.id - a.id);
                });
            }
        },
        computed: {
            momentDate() {
                return moment.tz(this.$props.createdAt.date, this.$props.createdAt.timezone).tz(this.$root.$data.timezone).format('MMM Do YYYY, h:mma z');
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
