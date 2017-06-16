<template>
    <div class="main-container">
      <div  @click="close()" :class="{overlay: renderNewMessage, isactive: renderNewMessage}"></div>
        <div class="main-content">
            <div class="main-header">
                <div class="container">
                  <h1 class="title header-xlarge">
                    <span class="text">Messages</span>
                    <button @click="close()" class="button main-action circle">
                        <svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#addition"></use></svg>
                    </button>
                    </h1>
                </div>
            </div>
            <NotificationPopup
              :active="notificationActive"
              :comes-from="notificationDirection"
              :symbol="notificationSymbol"
              :text="notificationMessage"
            />
            <div :class="{flyout: true, isactive: renderNewMessage}">
              <preview v-if="renderNewMessage" />
            </div>
            <div class="content-container">
                <div v-if="messageList" v-for="chat in messageList" class="messages-wrapper">
                  <router-link :to="{
                      name: 'detail',
                      params: {
                        subject: chat.attributes.subject,
                        sender_id : chat.attributes.sender_user_id,
                        recipient_id : chat.attributes.recipient_user_id,
                        sender_name: chat.attributes.sender_full_name
                      }
                    }">
                    <MessagePost
                        :name="chat.attributes.sender_full_name"
                        :image="chat.attributes.sender_image_url"
                        :day="chat.attributes.created_at.date"
                        :time="chat.attributes.created_at.date"
                        :header="chat.attributes.subject"
                        :message="chat.attributes.message"
                        :id="chat.id"
                     />
                  </router-link>
                </div>
            </div>
      </div>
    </div>
  </div>
</template>

<script>
    import Preview from './components/AddMessages.vue'
    import MessagePost from './components/MessagePost.vue'
    import UserNav from '../../commons/UserNav.vue'
    import NotificationPopup from '../../commons/NotificationPopup.vue'
    import socket from './websocket'
    import axios from 'axios'
    import _ from 'lodash'
    export default {
        name: 'messages',
        components: {
          Preview,
          UserNav,
          MessagePost,
          NotificationPopup
        },
        data() {
            return {
              renderNewMessage: false,
              user: this.$root.$data.global.user.id,
              notificationSymbol: '&#10003;',
              notificationMessage: 'Message Sent!',
              notificationActive: false,
              notificationDirection: 'top-right'
            }
        },
        computed: {
          messageList() {
            return this.$root.$data.global.messages
          }
        },
        methods: {
          close() {
            this.renderNewMessage = !this.renderNewMessage
          }
        },
        mounted() {
          this.$root.$data.global.currentPage = 'messages';

          let userId = this.$root.$data.global.user.id
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
                  this.$root.$data.global.messages = Object.values(data)
                    .map(e => e[e.length - 1])
                    .sort((a, b) => ((a.attributes.read_at == null || b.attributes.read_at == null) && (userId == a.attributes.recipient_user_id || userId == b.attributes.recipient_user_id) ? 1 : -1));
                  this.$root.$data.global.unreadMessages = response.data.data.filter(e => e.attributes.read_at == null && e.attributes.recipient_user_id == userId)
                }
              })
          let channel = socket.subscribe(`private-App.User.${window.Laravel.user.id}`);
          channel.bind('App\\Events\\MessageCreated', (data) => {
            let subject = data.data.attributes.subject
            this.$root.$data.global.detailMessages[subject] = this.$root.$data.global.detailMessages[subject] ? 
                this.$root.$data.global.detailMessages[subject].push(data.data) : [data.data]
            this.$root.$data.global.unreadMessages = _.flattenDeep(this.$root.$data.global.detailMessages).filter(e => e.attributes.read_at == null && e.attributes.recipient_user_id == userId)
            this.$root.$data.global.messages = Object.values(this.$root.$data.global.detailMessages)
              .map(e => e[e.length - 1])
              .sort((a, b) => ((a.attributes.read_at == null || b.attributes.read_at == null) && (userId == a.attributes.recipient_user_id || userId == b.attributes.recipient_user_id) ? 1 : -1));
          })
        },
        destroyed() {
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
                  this.$root.$data.global.messages = Object.values(data)
                      .map(e => e[e.length - 1])
                      .sort((a, b) => ((a.attributes.read_at == null || b.attributes.read_at == null) && (userId == a.attributes.recipient_user_id || userId == b.attributes.recipient_user_id) ? 1 : -1));
                  this.$root.$data.global.unreadMessages = response.data.data.filter(e => e.attributes.read_at == null && e.attributes.recipient_user_id == userId)
                }
            })
        }
    }
</script>
