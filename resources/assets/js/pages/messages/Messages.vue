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
              <router-link to="/detail" style="padding: 4px;"><MessagePost></MessagePost></router-link>
              <router-link to="/detail" style="padding: 4px;"><MessagePost></MessagePost></router-link>
              <router-link to="/detail" style="padding: 4px;"><MessagePost></MessagePost></router-link>
            </div>
      </div>
    </div>
  </div>
</template>

<script>
    import Preview from './components/AddMessages.vue'
    import MessagePost from './components/MessagePost.vue'
    import UserNav from '../../commons/UserNav.vue'
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
              isActive: null
            }
        },
        methods: {
          close() {
            this.renderNewMessage = !this.renderNewMessage
          }
        },
        computed: {
          getMessageList() {
            let data = [];
            console.log(`COMPUTED`);
            this.$http.get(`/api/v1/messages`)
              .then(response => {
                console.log(`CALLED`);
                data = response.data;
                console.log(`DATA`, data);
              })
            return data;
          }
        }
    }
</script>

<style>
</style>