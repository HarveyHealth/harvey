<template>
      <div class="main-container">
      <div  @click="close()" :class="{overlay: renderNewMessage, isactive: renderNewMessage}"></div>
      <UserNav :current-page="'messages'" />
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
            <div style="padding: 20px;">
                <div v-if="messageList" v-for="chat in messageList">
                  <router-link :to="{
                      name: 'detail',
                      params: {
                        subject: chat.attributes.subject,
                        sender_id : chat.attributes.sender_user_id,
                        recipient_id : chat.attributes.recipient_user_id,
                        sender_name: chat.attributes.sender_full_name
                      }
                    }" style="padding: 4px;">
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
    import channel from './websocket'
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
              messageList: this.$root.$data.global.messages,
              user: this.$root.$data.global.user.id,
              notificationSymbol: '&#10003;',
              notificationMessage: 'Message Sent!',
              notificationActive: false,
              notificationDirection: 'top-right'
            }
        },
        methods: {
          close() {
            this.renderNewMessage = !this.renderNewMessage
          }
        },
        mounted() {
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
                  this.$root.$data.global.unreadMessages = response.data.data.filter(e => e.attributes.read_at == null && e.attributes.recipient_user_id == this.$root.$data.global.user.id)
                }
              })
          channel.bind('App\\Events\\MessageCreated', (data) => {
            this.$root.$data.global.detailMessages[data.attributes.subject].push(data.data)
            this.$root.$data.global.detailMessages[data.attributes.subject].sort((a, b) => a.attributes.created_at - b.attributes.created_at)
            this.$root.$data.global.messages = Object.values(this.$root.$data.global.detailMessages)
              .map(e => e[e.length - 1])
              .sort((a, b) => {
                    if ((a.attributes.read_at == null || b.attributes.read_at == null) &&
                      (this.$root.$data.global.user.id == a.attributes.recipient_user_id || this.$root.$data.global.user.id == b.attributes.recipient_user_id)) {
                      return 1;
                    }
                    return -1;
                  });
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
                  this.$root.$data.global.messages = Object.values(data).map(e => e[e.length - 1]).sort((a, b) => {
                    if ((a.attributes.read_at == null || b.attributes.read_at == null) &&
                      (this.$root.$data.global.user.id == a.attributes.recipient_user_id || this.$root.$data.global.user.id == b.attributes.recipient_user_id)) {
                      return 1;
                    }
                    return -1;
                  });
                  this.messageList = this.$root.$data.global.messages;
                  this.$root.$data.global.unreadMessages = response.data.data.filter(e => e.attributes.read_at == null && e.attributes.recipient_user_id == this.$root.$data.global.user.id)
                }
            })
        }
    }
</script>
