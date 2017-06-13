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
                    .then(response => {
                        this.$root.$data.global.detailMessages[this.$props.header] = this.$root.$data.global.detailMessages[this.$props.header]
                            .reduce((acc, item) => {
                                if (item.id !== this.$props.id) {
                                    acc.push(item)
                                } else {
                                    acc.push(response.data.data)
                                }
                                return acc;
                            }, []);
                            this.$root.$data.global.messages = Object.values(this.$root.$data.global.detailMessages).map(e => e[e.length - 1])
                     })
            }
        }
    }
</script>