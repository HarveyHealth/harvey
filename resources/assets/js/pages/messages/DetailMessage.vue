<template>
    <div class="main-container">
        <div v-on:click="reply()" :class="{overlay: renderReply, isactive: renderReply}"></div>
        <UserNav />
        <div class="main-content">
            <div class="main-header">
                <div class="container container-backoffice">
                    <h1 class="heading-1">
                      <span class="text">{{ subject }}</span>                      
                    </h1>
                    <h3 class="font-sm copy-muted-2">
                      <router-link to="/messages">
                        <i class="fa fa-long-arrow-left"></i> Back to Messages
                      </router-link>
                    </h3>
                </div>
            </div>
            <div :class="{flyout: true, isactive: renderReply}">
                <Reply v-if="renderReply" :name="recipient_id != your_id ? recipient_full_name : sender_name" :senderId="sender_id" :header="subject" :id="user_id" />
            </div>
            <NotificationPopup
                :active="notificationActive"
                :comes-from="notificationDirection"
                :symbol="notificationSymbol"
                :text="notificationMessage"
            />
            <div class="content-container">
                <div class="container-message">
                    <div class="detail-wrap" v-if="detailList" v-for="detail in detailList">
                      <DetailPost
                        :id="detail.id"
                        :name="detail.attributes.sender_full_name"
                        :day="detail.attributes.created_at.date"
                        :time="detail.attributes.created_at.date"
                        :timezone="detail.attributes.created_at.timezone"
                        :header="detail.attributes.subject"
                        :message="detail.attributes.message"
                        :image="detail.attributes.sender_image_url"
                        :userId="detail.attributes.recipient_user_id"
                        :yourId="your_id"
                      />
                    </div>
                    <div class="button-wrapper">
                        <button class="button" @click="reply()">Reply</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Preview from './components/AddMessages.vue';
    import Reply from './components/ReplyMessages.vue';
    import DetailPost from './components/DetailPost.vue';
    import UserNav from '../../commons/UserNav.vue';
    import NotificationPopup from '../../commons/NotificationPopup.vue';
    import axios from 'axios';
    import socket from './websocket';
    import _ from 'lodash';
    export default {
        props: ['sender_id', 'subject', 'recipient_id', 'sender_name', 'recipient_full_name', 'thread_id'],
        name: 'messages',
        components: {
          Preview,
          UserNav,
          DetailPost,
          Reply,
          NotificationPopup
        },
        data() {
            return {
              renderNewMessage: false,
              renderReply: false,
              isActive: null,
              user: this.userName,
              your_id: window.Laravel.user.id,
              user_id: _.pull([this.$props.recipient_id, this.$props.sender_id], this.$root.$data.global.user.id)[0],
              notificationSymbol: '&#10003;',
              notificationMessage: 'Message Sent!',
              notificationActive: false,
              notificationDirection: 'top-right'
            };
        },
        computed: {
            detailList() {
                return this.$root.$data.global.detailMessages[this.$props.thread_id];
            }
        },
        methods: {
          close() {
            this.renderNewMessage = !this.renderNewMessage;
          },
          reply() {
            this.renderReply = !this.renderReply;
          },
          highlights(user) {
              return user === this.your_id;
          },
          makeThreadId(userOne, userTwo) {
            return userOne > userTwo ? `${userTwo}-${userOne}` : `${userOne}-${userTwo}`;
          },
          userName() {
              if (this.$root.$data.permissions === 'patient') {
                  let arr = this.$root.$data.global.practitioners;
                  return arr.filter(e => e.id === this.$route.params.id)[0].name;
              } else if (this.$root.$data.permissions === 'practitioner') {
                  let arr = this.$root.$data.global.patients;
                  return arr.filter(e => e.id === this.$route.params.id)[0].name;
              } else if (this.$root.$data.permissions === 'admin') {
                  let all = this.$root.$data.global.practitioners.concat(this.$root.$data.global.patients);
                  return all.filter(e => e.id === this.$route.params.id)[0].name;
              }
           }
        },
        mounted() {
            let channel = socket.subscribe(`private-App.User.${window.Laravel.user.id}`);
            channel.bind('App\\Events\\MessageCreated', (data) => {
                let subject = `${makeThreadId(data.data.attributes.sender_user_id, data.data.attributes.recipient_user_id)}-${data.data.attributes.subject}`;
                let userId = this.$root.$data.global.user.id;
                this.$root.$data.global.detailMessages[subject].push(data.data);
                this.$root.$data.global.detailMessages[subject].sort((a, b) => a.attributes.created_at - b.attributes.created_at);
                this.$root.$data.global.unreadMessages = _.flattenDeep(this.$root.$data.global.detailMessages)
                    .filter(e => e.attributes.read_at == null && e.attributes.recipient_user_id == userId);
                this.$root.$data.global.messages = Object.values(this.$root.$data.global.detailMessages)
                    .map(e => e[e.length - 1])
                    .sort((a, b) => ((a.attributes.read_at == null || b.attributes.read_at == null) && (userId == a.attributes.recipient_user_id || userId == b.attributes.recipient_user_id) ? 1 : -1));
            });
        },
        destroyed() {
            axios.get(`${this.$root.$data.apiUrl}/messages`)
                .then(response => {
                    let data = {};
                    let userId = this.$root.$data.global.user.id;
                    response.data.data.forEach(e => {
                        data[`${this.makeThreadId(e.attributes.sender_user_id, e.attributes.recipient_user_id)}-${e.attributes.subject}`] = data[`${this.makeThreadId(e.attributes.sender_user_id, e.attributes.recipient_user_id)}-${e.attributes.subject}`] ?
                            data[`${this.makeThreadId(e.attributes.sender_user_id, e.attributes.recipient_user_id)}-${e.attributes.subject}`] :
                            [];
                        data[`${this.makeThreadId(e.attributes.sender_user_id, e.attributes.recipient_user_id)}-${e.attributes.subject}`].push(e);
                    });
                    if (data) {
                        Object.values(data).map(e => _.uniq(e.sort((a, b) => a.attributes.created_at - b.attributes.created_at)));
                        this.$root.$data.global.detailMessages = data;
                        this.$root.$data.global.messages = Object.values(data)
                            .map(e => e[e.length - 1])
                            .sort((a, b) => ((a.attributes.read_at == null || b.attributes.read_at == null) && (userId == a.attributes.recipient_user_id || userId == b.attributes.recipient_user_id) ? 1 : -1));
                        this.$root.$data.global.unreadMessages = response.data.data.filter(e => e.attributes.read_at == null && e.attributes.recipient_user_id == userId);
                    }
            });
        }
    };
</script>
