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
                <DetailPost 
                    name="Alicia Keys"
                    day="chat.attributes.created_at.date.split('').splice(0, 10).join('')"
                    time="chat.attributes.created_at.date.split('').splice(11, 8).join('')"
                    subject="chat.attributes.subject"
                    message="chat.attributes.message"
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
    export default {
        props: ['id'],
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
              user: 'OTHER'
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
          }
        }
    }
</script>

<style>
</style>