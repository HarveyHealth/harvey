<template>
    <div class="main-container">
        <div  @click="close()" :class="{overlay: renderNewMessage, isactive: renderNewMessage}"></div>
            <div class="main-content">
                <div class="main-header">
                    <div class="container container-backoffice">
                        <h1 class="heading-1">
                            <span class="text">Messages</span>
                            <button v-if="loadingAddMessage()"
                            @click="close()" class="button main-action circle">
                                <svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#addition"></use></svg>
                            </button>
                            <ClipLoader class="main-action" style="background-color: transparent;" v-else :color="'#82BEF2'" :loading="true" />
                        </h1>
                        <p class="font-italic gray font-xs is-padding-left margin-bottom-0">Please note: doctors may take up to 24 hours to respond. The messaging service should not to be used for urgent matters. Please call 911 in the event of emergency.</p>
                    </div>
                </div>
                <NotificationPopup
                    :active="notificationActive"
                    :comes-from="notificationDirection"
                    :symbol="notificationSymbol"
                    :text="notificationMessage"
                />
                <div class="card empty-card" v-if="!messageList.length && !$root.$data.global.loadingMessages">
                    <p class="copy-muted font-md font-italic">You do not have any messages.</p>
                </div>
                <div class="card empty-card" v-if="$root.$data.global.loadingMessages">
                    <p class="copy-muted font-md font-italic">Your messages are loading...</p>
                </div>
                <div :class="{flyout: true, isactive: renderNewMessage}">
                    <preview v-if="renderNewMessage" />
                </div>
                <div v-for="chat in messageList" class="messages-wrapper">
                    <router-link :to="{
                        name: 'detail',
                        params: {
                        path: `${makeThreadId(chat.sender_user_id, chat.recipient_user_id)}-${chat.subject}`,
                        thread_id: `${makeThreadId(chat.sender_user_id, chat.recipient_user_id)}-${chat.subject}`,
                        subject: chat.subject,
                        sender_id : chat.sender_user_id,
                        recipient_id : chat.recipient_user_id,
                        sender_name: chat.sender_full_name,
                        recipient_full_name: chat.recipient_full_name,
                        }
                    }">
                    <MessagePost
                        :id="chat.id"
                        :created-at="chat.created_at"
                        :header="chat.subject"
                        :image="chat.sender_image_url"
                        :message="chat.message"
                        :name="chat.sender_user_id != user ? chat.sender_full_name : chat.recipient_full_name"
                    />
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Preview from './components/AddMessages.vue';
    import MessagePost from './components/MessagePost.vue';
    import UserNav from '../../commons/UserNav.vue';
    import { ClipLoader } from 'vue-spinner/dist/vue-spinner.min.js';
    import NotificationPopup from '../../commons/NotificationPopup.vue';
    import socket from './websocket';
    import _ from 'lodash';
    export default {
        name: 'messages',
        components: {
          Preview,
          UserNav,
          MessagePost,
          NotificationPopup,
          ClipLoader
        },
        data() {
            return {
              renderNewMessage: false,
              user: Laravel.user.id,
              notificationSymbol: '&#10003;',
              notificationMessage: 'Message Sent!',
              notificationActive: false,
              notificationDirection: 'top-right',
              messageList: []
            };
        },
        computed: {
            messageState() {
                let messages = this.$root.$data.global.messages;
                if (!this.$root.$data.global.messages.length) {
                    this.setMessages([]);
                    return [];
                } else {
                    let messageState = messages.sort((a, b) => b.id - a.id);
                    this.setMessages(messageState);
                    return messageState;
                }
            }
        },
        watch: {
            messageState(val) {
                if (!val) {
                    let messages = this.$root.$data.global.messages;
                    if (!messages.length) {
                        this.setMessages([]);
                        return [];
                    } else {
                        let messageState = messages.sort((a, b) => b.id - a.id);
                        this.setMessages(messageState);
                        return messageState;
                    }
                }
            }
        },
        methods: {
          close() {
            this.renderNewMessage = !this.renderNewMessage;
          },
          setMessages(data) {
              console.log(!data.length)
              if (!data.length) {
                  this.messageList = [];
              } else {
                  this.messageList = data.sort((a, b) => b.id - a.id).map(e => e.attributes);
              }
          },
          makeThreadId(userOne, userTwo) {
            return userOne > userTwo ? `${userTwo}-${userOne}` : `${userOne}-${userTwo}`;
          },
          loadingAddMessage() {
              const global = this.$root.$data.global;
              if (Laravel.user.user_type === 'admin') {
                  return !global.loadingAppointments && !global.loadingPractitioners && !global.loadingPatients;
              } else if (Laravel.user.user_type === 'practitioner') {
                  return !global.loadingAppointments && !global.loadingPatients;
              } else if (Laravel.user.user_type === 'patient') {
                  return !global.loadingAppointments && !global.loadingPractitioners;
              }
          }
        },
        mounted() {
            this.$root.$data.global.currentPage = 'messages';

            let userId = this.$root.$data.global.user.id;
            let channel = socket.subscribe(`private-App.User.${window.Laravel.user.id}`);
            channel.bind('App\\Events\\MessageCreated', (data) => {
                let ws = data.data;
                let subject = `${this.makeThreadId(ws.attributes.sender_user_id, ws.attributes.recipient_user_id)}-${ws.attributes.subject}`;
                if (this.$root.$data.global.detailMessages[subject]) {
                    this.$root.$data.global.detailMessages[subject].push(ws);
                } else {
                    this.$root.$data.global.detailMessages[subject] = [ws];
                }
                this.$root.$data.global.unreadMessages = _.flattenDeep(Object.values(this.$root.$data.global.detailMessages)).filter(e => e.attributes.read_at == null && e.attributes.recipient_user_id == userId);
                this.$root.$data.global.messages = Object.values(this.$root.$data.global.detailMessages)
                    .map(e => e[e.length - 1])
                    .sort((a, b) => b.id - a.id);
                this.setMessages(this.$root.$data.global.messages);
            });
            this.$root.getMessages();
        }
    };
</script>
