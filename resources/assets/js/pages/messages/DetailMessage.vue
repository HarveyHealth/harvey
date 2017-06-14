<template>
    <div class="main-container">
      <div  v-on:click="reply()" :class="{overlay: renderReply, isactive: renderReply}"></div>
      <UserNav />
        <div class="main-content">
            <div class="main-header">
                <div class="container">
                  <h1 class="title header-xlarge">
                    <span class="text">Message Details</span>
                    </h1>
                </div>
            </div>
            <div :class="{flyout: true, isactive: renderReply}">
             <Reply v-if="renderReply" :name="sender_name" :header="subject" :id="user_id" />
            </div>
            <NotificationPopup
                :active="notificationActive"
                :comes-from="notificationDirection"
                :symbol="notificationSymbol"
                :text="notificationMessage"
            />
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
                        :userId="detail.attributes.recipient_user_id"
                    />
                </div>
              </div>
            <div class="inline-centered" style="background-color: white; width: 1000px; padding-bottom: 10px;">
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
    import NotificationPopup from '../../commons/NotificationPopup.vue'
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
          Reply,
          NotificationPopup
        },
        data() {
            return {
              renderNewMessage: false,
              renderReply: false,
              isActive: null,
              user: this.userName,
              user_id: _.pull([this.$props.recipient_id, this.$props.sender_id], this.$root.$data.global.user.id)[0],
              notificationSymbol: '&#10003;',
              notificationMessage: 'Message Sent!',
              notificationActive: false,
              notificationDirection: 'top-right'
            }
        },
        computed: {
            detailList() {
                return this.$root.$data.global.detailMessages[this.$props.subject]
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
                this.$root.$data.global.detailMessages[data.data.attributes.subject].push(data.data)
                this.$root.$data.global.detailMessages[data.data.attributes.subject].sort((a, b) => a.attributes.created_at - b.attributes.created_at)
                this.$root.$data.global.messages = Object.values(this.$root.$data.global.detailMessages).map(e => e[e.length - 1])
                this.detailList = this.$root.$data.global.detailMessages
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
                    })
                    this.$root.$data.global.unreadMessages = response.data.data.filter(e => e.attributes.read_at == null && e.attributes.recipient_user_id == this.$root.$data.global.user.id)
                }
            });
        }
    }
</script>