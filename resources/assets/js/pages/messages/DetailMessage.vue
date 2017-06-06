<template>
      <div class="main-container">
      <div  v-on:click="reply()" :class="{overlay: renderReply, isactive: renderReply}"></div>
      <UserNav />
        <div class="main-content">
            <div class="main-header">
                <div class="container">
                  <h1 class="title header-xlarge">
                    <span class="text">Details</span>
                    </h1>
                </div>
            </div>
            <div :class="{flyout: true, isactive: renderReply}">
             <Reply v-if="renderReply" :name="sender_name" :header="subject" :id="user_id" />
            </div>
            <div style="padding: 20px;">
                <div style="background-color: white; width: 1000px;" class="border-message">
                    <h2 style="margin: auto; padding: 20px 40px;">{{ subject }}</h2>
                </div>
              <div class="container-detail">
                <div style="height: auto;" v-if="detailList" v-for="detail in detailList">
                    <DetailPost 
                        :id="detail.id"
                        :name="detail.attributes.sender_full_name"
                        :day="detail.attributes.created_at.date"
                        :time="detail.attributes.created_at.date"
                        :header="detail.attributes.subject"
                        :message="detail.attributes.message"
                        :image="detail.attributes.sender_image_url"
                    />
                </div>
              </div>
            <div class="inline-centered" style="background-color: white; width: 1000px;">
                <button class="button" @click="reply()">Reply</button>
            </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
    import Preview from './components/AddMessages.vue'
    import Reply from './components/ReplyMessages.vue'
    import DetailPost from './components/DetailPost.vue'
    import UserNav from '../../commons/UserNav.vue'
    import axios from 'axios'
    import channel from './websocket'
    import _ from 'lodash'
    export default {
        props: ['sender_id', 'subject', 'recipient_id', 'sender_name'],
        name: 'messages',
        components: {
          Preview,
          UserNav,
          DetailPost,
          Reply
        },
        data() {
            return {
              renderNewMessage: false,
              renderReply: false,
              isActive: null,
              user: this.userName,
              user_id: this.$props.recipient_id,
              detailList: this.$root.$data.global.detailMessages[this.$props.subject]
            }
        },
        methods: {
          close() {
            this.renderNewMessage = !this.renderNewMessage
          },
          reply() {
            this.renderReply = !this.renderReply
          },
          userName() {
              if (this.$root.$data.global.user.attributes.user_type === 'patient') {
                  let arr = this.$root.$data.global.practitioners
                  return arr.filter(e => e.id === this.$route.params.id)[0].name
              } else if (this.$root.$data.global.user.attributes.user_type === 'practitioner') {
                  let arr = this.$root.$data.global.patients
                  return arr.filter(e => e.id === this.$route.params.id)[0].name
              } else if (this.$root.$data.global.user.attributes.user_type === 'admin') {
                  let all = this.$root.$data.global.practitioners.concat(this.$root.$data.global.patients)
                  return all.filter(e => e.id === this.$route.params.id)[0].name
              }
           }
        },
        mounted() {
            channel.bind('App\\Events\\MessageCreated', (data) => {
                this.$root.$data.global.detailMessages[data.attributes.subject].push(data.data)
                this.$root.$data.global.detailMessages[data.attributes.subject].sort((a, b) => a.attributes.created_at - b.attributes.created_at)
                this.$root.$data.global.messages = Object.values(this.$root.$data.global.detailMessages).map(e => e[e.length -1])
                this.detailList = this.$root.$data.global.detailMessages
            })
        }
    }
</script>