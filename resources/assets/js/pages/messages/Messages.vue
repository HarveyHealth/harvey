<template>
      <div class="main-container">
      <div  v-on:click="close()" :class="{overlay: renderNewMessage, isactive: renderNewMessage}"></div>
      <UserNav />
        <div class="main-content">
            <div class="main-header">
                <div class="container">
                  <h1 class="title header-xlarge">
                    <span class="text">Messages</span>
                    <button v-on:click="close()" class="button main-action circle">
                        <svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#addition"></use></svg>
                    </button>
                    </h1>
                </div>
            </div>
            <preview v-if="renderNewMessage" />
            <div style="padding: 20px;">
                <div v-for="chat in messageList">
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
              isActive: null,
              messageList: this.$root.$data.global.messages,
              detailList: this.$root.$data.global.detailMessages,
              allUsers: this.$root.$data.global.allUsers
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
                response.data.data.forEach(e => {
                  this.$root.$data.global.detailMessages[Number(e.attributes.sender_user_id)] = this.$root.$data.global.detailMessages[Number(e.attributes.sender_user_id)] ?  
                      this.$root.$data.global.detailMessages[Number(e.attributes.sender_user_id)] :
                      {};
                  this.$root.$data.global.detailMessages[Number(e.attributes.sender_user_id)][e.attributes.subject] = this.$root.$data.global.detailMessages[Number(e.attributes.sender_user_id)][e.attributes.subject] ?
                      this.$root.$data.global.detailMessages[Number(e.attributes.sender_user_id)][e.attributes.subject] :
                      [];
                  if (!_.includes(this.$root.$data.global.detailMessages[Number(e.attributes.sender_user_id)][e.attributes.subject], e)) {
                    this.$root.$data.global.detailMessages[Number(e.attributes.sender_user_id)][e.attributes.subject].push(e);
                  }
                  this.$root.$data.global.detailMessages[Number(e.attributes.sender_user_id)][e.attributes.subject] = _.uniq(this.$root.$data.global.detailMessages[Number(e.attributes.sender_user_id)][e.attributes.subject])
                });
                this.detailList = this.$root.$data.global.detailMessages;
                this.$root.$data.global.messages = Object.values(this.$root.$data.global.detailMessages)
                    .map(e => Object.values(e)[0][Object.values(e)[0].length - 1]);
              })
        }
    }
</script>