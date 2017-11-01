<template>
    <div class="main-container">
      <div  @click="close()" :class="{overlay: renderNewMessage, isactive: renderNewMessage}"></div>
        <div class="main-content">
            <div class="main-header">
                <div class="container container-backoffice">
                  <h1 class="heading-1">
                    <span class="text">Messages</span>
                    <button v-if="!$root.$data.global.loadingMessages && !$root.$data.global.loadingAppointments && !$root.$data.global.loadingConfirmedUsers" @click="close()" class="button main-action circle">
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
                      thread_id: `${makeThreadId(chat.attributes.sender_user_id, chat.attributes.recipient_user_id)}-${chat.attributes.subject}`,
                      subject: chat.attributes.subject,
                      sender_id : chat.attributes.sender_user_id,
                      recipient_id : chat.attributes.recipient_user_id,
                      sender_name: chat.attributes.sender_full_name,
                      recipient_full_name: chat.attributes.recipient_full_name,
                    }
                  }">
                  <MessagePost
                      :name="chat.attributes.sender_user_id != user ? chat.attributes.sender_full_name : chat.attributes.recipient_full_name"
                      :image="chat.attributes.sender_image_url"
                      :day="chat.attributes.created_at.date"
                      :time="chat.attributes.created_at.date"
                      :timezone="chat.attributes.created_at.timezone"
                      :header="chat.attributes.subject"
                      :message="chat.attributes.message"
                      :id="chat.id"
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
    import NotificationPopup from '../../commons/NotificationPopup.vue';
    import socket from './websocket';
    import _ from 'lodash';
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
              user: Laravel.user.id,
              notificationSymbol: '&#10003;',
              notificationMessage: 'Message Sent!',
              notificationActive: false,
              notificationDirection: 'top-right'
            };
        },
        computed: {
          messageList() {
            let messages = this.$root.$data.global.messages || [];
            return messages.sort((a, b) => new Date(b.attributes.created_at.date) - new Date(a.attributes.created_at.date));
          }
        },
        // watch: {
        //     messageList(val) {
        //         if (!val) {
        //             let messages = this.$root.$data.global.messages || [];
        //             return messages.sort((a, b) => new Date(b.attributes.created_at.date) - new Date(a.attributes.created_at.date));
        //         }
        //     }
        // },
        methods: {
          close() {
            this.renderNewMessage = !this.renderNewMessage;
          },
          makeThreadId(userOne, userTwo) {
            return userOne > userTwo ? `${userTwo}-${userOne}` : `${userOne}-${userTwo}`;
          }
        },
        mounted() {
          this.$root.$data.global.currentPage = 'messages';

          let userId = this.$root.$data.global.user.id;
          let channel = socket.subscribe(`private-App.User.${window.Laravel.user.id}`);
          channel.bind('App\\Events\\MessageCreated', (data) => {
            let subject = `${this.makeThreadId(data.data.attributes.sender_user_id, data.data.attributes.recipient_user_id)}-${data.data.attributes.subject}`;
            this.$root.$data.global.detailMessages[subject] = this.$root.$data.global.detailMessages[subject] ?
                this.$root.$data.global.detailMessages[subject].push(data.data) : [data.data];
            this.$root.$data.global.unreadMessages = _.flattenDeep(this.$root.$data.global.detailMessages).filter(e => e.attributes.read_at == null && e.attributes.recipient_user_id == userId);
            this.$root.$data.global.messages = Object.values(this.$root.$data.global.detailMessages)
              .sort((a, b) => new Date(b.attributes.created_at.date) - new Date(a.attributes.created_at.date));
          });
          console.log(`ROOT`, this.$root)
          this.$root.getMessages();
          this.$root.getConfirmedUsers();
        }
    };
</script>
