<template>
    <div class="main-container">
        <div @click="reply()" :class="{overlay: renderReply, isactive: renderReply}"></div>
        <div class="main-content">
            <div class="main-header">
                <div class="container container-backoffice">
                    <h1 class="heading-1">
                      <span class="text word-wrap">{{ chat && chat.subject ? chat.subject : 'Loading your messages...' }}</span>
                    </h1>
                    <h3 class="font-sm copy-muted-2">
                      <router-link to="/messages">
                        <i class="fa fa-long-arrow-left"></i> Back to Messages
                      </router-link>
                    </h3>
                </div>
            </div>
            <div :class="{flyout: true, isactive: renderReply}">
                <Reply 
                    v-if="renderReply && chat" 
                    :name="chat.recipient_user_id != your_id ? chat.recipient_full_name : chat.sender_full_name" 
                    :senderId="chat.sender_user_id" 
                    :header="chat.subject" 
                    :id="other_id" 
                />
            </div>
            <NotificationPopup
                :active="notificationActive"
                :comes-from="notificationDirection"
                :symbol="notificationSymbol"
                :text="notificationMessage"
            />
            <div v-if="loading" class="content-container">
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
            path: String
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
              your_id: window.Laravel.user.id,
              notificationSymbol: '&#10003;',
              notificationMessage: 'Message Sent!',
              notificationActive: false,
              notificationDirection: 'top-right',
              detailList: []
            };
        },
        computed: {
            stateDetail() {
                if (this.$root.$data.global.detailMessages[this.$props.path]) {
                    let messages = this.$root.$data.global.detailMessages[this.$props.path];
                    let details = messages.sort((a, b) => a.id - b.id);
                    this.setDetails(details);
                    return details;
                } else {
                    return false;
                }
            },
            loading() {
                return this.$root.$data.global.detailMessages[this.$props.path] && this.$root.$data.global.detailMessages[this.$props.path].length;
            },
            chat() {
                if (this.$root.$data.global.detailMessages[this.$props.path]) {
                    return this.$root.$data.global.detailMessages[this.$props.path][0].attributes;
                }
                return false;
            },
            other_id() {
                if (this.$root.$data.global.detailMessages[this.$props.path] && this.$root.$data.global.detailMessages[this.$props.path][0]) {
                    return _.pull([this.$root.$data.global.detailMessages[this.$props.path][0].attributes.recipient_user_id, this.$root.$data.global.detailMessages[this.$props.path][0].attributes.sender_user_id], this.your_id)[0];
                }
                return false;
            }
        },
        watch: {
            stateDetail(val) {
                if (!val) {
                    let messages = this.$root.$data.global.detailMessages[this.$props.path];
                    let details = messages.sort((a, b) => a.id - b.id);
                    this.setDetails(details);
                    return details;
                }
            },
            loading(val) {
                if (!val) {
                    return this.$root.$data.global.detailMessages[this.$props.path] && this.$root.$data.global.detailMessages[this.$props.path].length;
                }
            },
            chat(val) {
                if (!val && this.$root.$data.global.detailMessages[this.$props.path] && this.$root.$data.global.detailMessages[this.$props.path].length) {
                    return this.$root.$data.global.detailMessages[this.$props.path][0].attributes;
                }
            },
            other_id(val) {
                if (!val && this.$root.$data.global.detailMessages[this.$props.path] && this.$root.$data.global.detailMessages[this.$props.path].length) {
                    return _.pull([this.$root.$data.global.detailMessages[this.$props.path][0].attributes.recipient_user_id, this.$root.$data.global.detailMessages[this.$props.path][0].attributes.sender_user_id], this.your_id)[0];
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
            this.$root.$data.global.currentPage = 'details';
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
            this.$root.getMessages();
        }
    };
</script>
