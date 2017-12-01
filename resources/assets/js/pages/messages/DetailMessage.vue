<template>
    <div class="main-container">
        <div @click="reply()" :class="{overlay: renderReply, isactive: renderReply}"></div>
        <UserNav />
        <div class="main-content">
            <div class="main-header">
                <div class="container container-backoffice">
                    <h1 class="heading-1">
                      <span class="text word-wrap">{{ subject }}</span>
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
                    <div class="detail-wrap" v-for="detail in detailList">
                      <DetailPost
                        :id="detail.id"
                        :created-at="detail.attributes.created_at"
                        :header="detail.attributes.subject"
                        :image="detail.attributes.sender_image_url"
                        :message="detail.attributes.message"
                        :name="detail.attributes.sender_full_name"
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
    import socket from './websocket';
    import _ from 'lodash';
    export default {
        props: {
            sender_id: String,
            subject: String,
            recipient_id: String,
            sender_name: String,
            recipient_full_name: String,
            thread_id: String
        },
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
              notificationDirection: 'top-right',
              detailList: this.$root.$data.global.detailMessages[this.$props.thread_id].sort((a, b) => a.id - b.id)
            };
        },
        computed: {
            stateDetail() {
                let messages = this.$root.$data.global.detailMessages[this.$props.thread_id];
                let details = messages.sort((a, b) => a.id - b.id);
                this.setDetails(details);
                return details;
            }
        },
        watch: {
            stateDetail(val) {
                if (!val) {
                    let messages = this.$root.$data.global.detailMessages[this.$props.thread_id];
                    let details = messages.sort((a, b) => a.id - b.id);
                    this.setDetails(details);
                    return details;
                }
            }
        },
        methods: {
          close() {
            this.renderNewMessage = !this.renderNewMessage;
          },
          setDetails(data) {
              this.detailList = data.sort((a, b) => a.id - b.id);
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
                let ws = data.data;
                let subject = `${this.makeThreadId(ws.attributes.sender_user_id, ws.attributes.recipient_user_id)}-${ws.attributes.subject}`;
                let userId = this.$root.$data.global.user.id;
                if (this.$root.$data.global.detailMessages[subject]) {
                    this.$root.$data.global.detailMessages[subject].push(ws);
                } else {
                    this.$root.$data.global.detailMessages[subject] = [ws];
                }
                this.$root.$data.global.unreadMessages = _.flattenDeep(Object.values(this.$root.$data.global.detailMessages))
                    .filter(e => e.attributes.read_at == null && e.attributes.recipient_user_id == userId);
                this.$root.$data.global.messages = Object.values(this.$root.$data.global.detailMessages)
                    .map(e => e[e.length - 1])
                    .sort((a, b) => ((a.attributes.read_at == null || b.attributes.read_at == null) && (userId == a.attributes.recipient_user_id || userId == b.attributes.recipient_user_id) ? 1 : -1));
                this.setDetails(this.$root.$data.global.detailMessages[subject]);
            });
        }
    };
</script>
