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
                    .then(resp => {
                         axios.get(`${this.$root.$data.apiUrl}/messages`)
                            .then(response => {
                                let data = {};
                                response.data.data.forEach(e => {
                                    data[e.attributes.subject] = data[e.attributes.subject] ?
                                        data[e.attributes.subject] :
                                        [];
                                    data[e.attributes.subject].push(e);
                                });
                                if (data) {
                                    Object.values(data).map(e => _.uniq(e.sort((a, b) => a.attributes.created_at - b.attributes.created_at)));
                                    this.$root.$data.global.detailMessages = data;
                                    this.$root.$data.global.messages = Object.values(data).map(e => e[e.length - 1]).sort((a, b) => {
                                        if ((a.attributes.read_at == null || b.attributes.read_at == null) &&
                                        (this.$root.$data.global.user.id == a.attributes.recipient_user_id || this.$root.$data.global.user.id == b.attributes.recipient_user_id)) {
                                            return 1;
                                        }
                                        return -1;
                                    });
                                    this.messageList = this.$root.$data.global.messages;
                                }
                            })
                     })
            }
        }
    }
</script>