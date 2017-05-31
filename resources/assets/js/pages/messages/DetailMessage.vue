<template>
      <div class="main-container">
      <div  v-on:click="reply()" :class="{overlay: renderReply, isactive: renderReply}"></div>
      <UserNav />
        <div class="main-content">
            <div class="main-header">
                <div class="container">
                  <h1 class="title header-xlarge">
                    <span class="text">Details</span>
                    <button v-on:click="reply()" class="button main-action circle">
                        <svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#addition"></use></svg>
                    </button>
                    </h1>
                </div>
            </div>
            <Reply v-if="renderReply" />
            <div style="padding: 20px;">
              <div style="background-color: white; height: 80%;">
                <div style="margin: 0 20px;">
                    <h1>SUBJECT</h1>
                </div>
                <DetailPost 
                    name="Alicia Keys"
                    day="chat.attributes.created_at.date"
                    time="chat.attributes.created_at.date"
                    subject="chat.attributes.subject"
                    message="chat.attributes.message"
                    image="http://res.cloudinary.com/dzllxh0km/image/upload/v1493268974/smqbmnavbzfsfssjf1hp.jpg"
                />
               <DetailPost 
                    name="Alicia Keys"
                    day="chat.attributes.created_at.date"
                    time="chat.attributes.created_at.date"
                    subject="chat.attributes.subject"
                    message="chat.attributes.message"
                    image="http://res.cloudinary.com/dzllxh0km/image/upload/v1493268974/smqbmnavbzfsfssjf1hp.jpg"
                />
                <div>
                  <div class="inline-centered">
                    <button class="button" v-on:click="reply()">Reply</button>
                </div>
                </div>
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
    export default {
        props: ['id', 'subject'],
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
              subject: 'Hello'
            }
        },
        methods: {
          newMessage() {
          },
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
            // axios.get(``)
            //     .then(response => {})
            //     .catch(error => {})
        }
    }
</script>