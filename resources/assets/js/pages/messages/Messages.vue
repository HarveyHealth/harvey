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
                  if (!_.includes(data[Number(e.attributes.sender_user_id)][e.attributes.subject], data[Number(e.attributes.sender_user_id)][e.attributes.subject].id)) {
                    data[Number(e.attributes.sender_user_id)][e.attributes.subject].push(e);
                    data[Number(e.attributes.sender_user_id)][e.attributes.subject] = _.uniq(data[Number(e.attributes.sender_user_id)][e.attributes.subject]);
                  }
                });
                this.$root.$data.global.detailMessages = data;
                this.$root.$data.global.messages = _.flatten(Object.values(data)
                    .map(e => Object.values(e).map(ele => ele[ele.length - 1])));
                this.messageList = this.$root.$data.global.messages;
              })
        }
    }
</script>