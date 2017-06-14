<template>
  <div class="nav-bar">
    <button class="menu-button"
      @click="handleMenu(null)"
    >
      <i :class="menuIcon"></i>
    </button>
    <nav class="admin-nav">
      <router-link to="/" class="nav-bar-account"
        @click.native="handleMenu(false)">
        <svg class="harvey-mark"><use xlink:href="#harvey-logo" /></svg>
      </router-link>
      <router-link class="admin-nav-link" to="/" title="Dashboard"
        @click.native="handleMenu(false)">
        <svg class="icon icon-nav-bar"><use xlink:href="#person" /></svg>
        <div class="text">Dashboard</div>
      </router-link>
      <router-link class="admin-nav-link" to="/appointments" title="Appointments"
        @click.native="handleMenu(false)">
        <svg class="icon icon-nav-bar"><use xlink:href="#appointments" /></svg>
        <div class="text">Appointments</div>
      </router-link>
      <!--
      <router-link class="admin-nav-link" to="/lab_orders" title="Lab Orders">
        <i class="fa fa-eyedropper icon icon-nav-bar"></i>
        <div class="text">Lab Orders</div>
      </router-link>
      -->
      <router-link class="admin-nav-link" to="/messages" title="Messages" :class="{unread: unread.length > 0}"
        @click.native="handleMenu(false)">
        <i class="fa fa-envelope-o icon icon-nav-bar"></i>
        <div class="text">Messages</div>
      </router-link>
      <div class="release">©2017 Harvey, Inc.</div>
      <a href="/logout" class="admin-nav-link logout" title="Logout">
        <svg class="icon icon-nav-bar"><use xlink:href="#logout"/></svg>
        <div class="text">Logout</div>
      </a>
    </nav>
  </div>
</template>

<script>
  import axios from 'axios'
  import channel from '../pages/messages/websocket'
  export default {
    data() {
      return {
      }
    },
    computed: {
      unread() {
        return this.$root.$data.global.unreadMessages;
      }
    },
    computed: {
      menuIcon() {
        return {
          'fa': true,
          'fa-close': this.$root.$data.global.menuOpen,
          'fa-navicon': !this.$root.$data.global.menuOpen
        }
      }
    },
    methods: {
      handleMenu(force) {
        // Added delay to allow time for new component to render in the router-view
        if (force === null) {
          this.$root.$data.global.menuOpen = !this.$root.$data.global.menuOpen;
        } else {
          setTimeout(() => this.$root.$data.global.menuOpen = force, 200);
        }
      }
    },
    beforeMount() {
      axios.get(`${this.$root.$data.apiUrl}/messages`)
        .then(response => {
          let unread = response.data.data.filter(e => e.attributes.read_at == null && e.attributes.recipient_user_id == this.$root.$data.global.user.id)
          this.unread = unread.length > 0 ? true : false
        })
    }
  }
</script>
