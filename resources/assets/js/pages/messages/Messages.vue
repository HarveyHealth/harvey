<template>
      <div class="main-container">
      <div  @click="close()" :class="{overlay: renderNewMessage, isactive: renderNewMessage}"></div>
      <UserNav />
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
            <div :class="{flyout: true, isactive: renderNewMessage}">
              <preview v-if="renderNewMessage" />
            </div>
            <div style="padding: 20px;">
                <div v-if="messageList" v-for="chat in messageList">
                  <router-link :to="{
                      name: 'detail',
                      params: {
                        subject: chat.attributes.subject,
                        sender_id : Number(chat.attributes.sender_user_id),
                        recipient_id : Number(chat.attributes.recipient_user_id),
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
                        :read="chat.attributes.read_at"
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
    import socket from './websocket'
    import axios from 'axios'
    import _ from 'lodash'
    export default {
        name: 'messages',
        components: {
          Preview,
          UserNav,
          MessagePost
        },
        data() {
            return {
              renderNewMessage: false,
              messageList: this.$root.$data.global.messages
            }
        },
        methods: {
          close() {
            this.renderNewMessage = !this.renderNewMessage
          }
        },
        mounted() {
          axios.get(`/api/v1/messages?recipient_user_id=${this.$root.$data.global.user.id}`)
              .then(response => {
                let data = {};
                response.data.data.forEach(e => {
                  data[Number(e.attributes.sender_user_id)] = data[Number(e.attributes.sender_user_id)] ?  
                      data[Number(e.attributes.sender_user_id)] :
                      {};
                  data[Number(e.attributes.sender_user_id)][e.attributes.subject] = data[Number(e.attributes.sender_user_id)][e.attributes.subject] ?
                      data[Number(e.attributes.sender_user_id)][e.attributes.subject] :
                      [];
                  data[Number(e.attributes.sender_user_id)][e.attributes.subject].push(e);
                  if (data[this.$root.$data.global.user.id] && data[this.$root.$data.global.user.id][e.attributes.subject]) {
                    data[this.$root.$data.global.user.id][e.attributes.subject].push(e);
                  }
                });
                let object = data[this.$root.$data.global.user.id];
                delete data[this.$root.$data.global.user.id];
                _.each(data, (value, key) => {
                      _.each(object, (v, k) => {
                          _.each(value, (val, ki) => {
                              if (ki == k) {
                                object[k] = object[k].concat(v);
                              }
                          });
                      });
                });
                _.each(object, (val, key) => {
                  object[key] = _.uniq(val);
                })
                this.$root.$data.global.messages = Object.values(object).map(e => e[e.length -1])
                this.$root.$data.global.detailMessages = object;
                this.messageList = this.$root.$data.global.messages;
              })
          let channel = socket.subscribe(`private-App.User.${this.$root.$data.global.user.id}`);
          channel.bind('MessageCreated', (data) => {
            this.$root.$data.global.detailMessages[this.$root.$data.global.user.id][data.subject].push(data.data)
            this.$root.$data.global.detailMessages[this.$root.$data.global.user.id][data.subject].sort((a, b) => a.attributes.created_at - b.attributes.created_at)
            this.$root.$data.global.messages = Object.values(data[this.$root.$data.global.user.id]).map(e => e[e.length -1])
          })
        }
    }
</script>