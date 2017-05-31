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
                  <router-link :to="'/detail/' + chat.attributes.sender_user_id" style="padding: 4px;">
                    <MessagePost
                        :name="chat.attributes.sender_full_name"
                        :image="chat.attributes.sender_image_url"
                        :day="chat.attributes.created_at.date"
                        :time="chat.attributes.created_at.date"
                        :subject="chat.attributes.subject"
                        :message="chat.attributes.message"
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
                this.messageList = response.data.data;
                this.$root.$data.global.messages = response.data.data;
                this.$root.$data.global.allUsers = this.$root.$data.global.practitioners
                  .concat(this.$root.$data.global.patients)
                  .concat([this.$root.$data.global.user]);
                this.allUsers = this.$root.$data.global.practitioners
                  .concat(this.$root.$data.global.patients)
                  .concat([this.$root.$data.global.user]);
              })
        }
    }
</script>