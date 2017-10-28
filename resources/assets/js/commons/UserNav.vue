<template>
  <div class="nav-bar" v-if="$root.$data.global.currentPage || State('misc.currentPage')">

    <button class="menu-button"
      @click="handleMenu(null)"
    >
      <i :class="menuIcon"></i>
    </button>

    <nav class="admin-nav">
      <router-link to="/" class="nav-bar-account"
        @click.native="handleMenu(false, 'dashboard')">
        <svg class="harvey-mark"><use xlink:href="#harvey-logo" /></svg>
      </router-link>

      <router-link to="/" title="Dashboard"
        :class="currentPageCheck('dashboard')"
        @click.native="handleMenu(false, 'dashboard')">
        <i class="fa fa-dashboard icon icon-nav-bar"></i>
        <div class="text">Dashboard</div>
      </router-link>

      <router-link to="/appointments" title="Appointments"
        :class="currentPageCheck('appointments')"
        @click.native="handleMenu(false, 'appointments')">
        <i class="fa fa-calendar icon icon-nav-bar"></i>
        <div class="text">Appointments</div>
      </router-link>

      <router-link to="/lab_orders" title="Lab Orders"
        :class="currentPageCheck('lab-orders')"
        @click.native="handleMenu(false, 'lab-orders')">
        <i class="fa fa-eyedropper icon icon-nav-bar"></i>
        <div class="text">Lab Orders</div>
      </router-link>

      <router-link to="/messages" title="Messages"
        :class="currentPageCheck('messages', unread)"
        @click.native="handleMenu(false, 'messages')">
        <i class="fa fa-envelope-o icon icon-nav-bar"></i>
        <div class="unread-dot"></div>
        <div class="text">Messages</div>
      </router-link>

      <!-- LEAVE THESE COMMENTS HERE -->

       <!-- <router-link to="/records" title="Records"
        :class="currentPageCheck('records')"
        @click.native="handleMenu(false, 'records')">
        <i class="fa fa-files-o icon icon-nav-bar"></i>
        <div class="text">Records</div>
      </router-link>  -->

       <router-link
       to="/settings" title="Settings"
        :class="currentPageCheck('settings')"
        @click.native="handleMenu(false, 'settings')">
        <i class="fa fa-cog icon icon-nav-bar"></i>
        <div class="text">Settings</div>
      </router-link>

      <router-link
        v-if="user === 'admin'"
        to="/clients" title="Recent Clients"
        :class="currentPageCheck('clients')"
        @click.native="handleMenu(false, 'clients')">
        <i class="fa fa-users icon icon-nav-bar"></i>
        <div class="text">Clients</div>
      </router-link>

      <router-link to="/profile" title="Profile"
                   :class="currentPageCheck('profile')"
                   @click.native="handleMenu(false, 'profile')">
        <i class="fa fa-user icon icon-nav-bar"></i>
        <div class="text">Profile</div>
      </router-link>

      <a href="/logout" class="admin-nav-link logout" title="Logout">
        <i class="fa fa-sign-out icon icon-nav-bar"></i>
        <div class="text">Logout</div>
      </a>

    </nav>
  </div>
</template>

<script>
  import axios from 'axios';
  import socket from '../pages/messages/websocket';
  export default {
    computed: {
      // Toggles font-awesome class name depending on state of menu
      menuIcon() {
        return {
          'fa': true,
          'fa-close': this.$root.$data.global.menuOpen,
          'fa-navicon': !this.$root.$data.global.menuOpen
        };
      },
      // Checks to see if there are any unread messages
      unread() {
        return this.$root.$data.global.unreadMessages.length > 0;
      },
      user() {
        return this.$root.$data.permissions;
      }
    },
    methods: {
      // Updates class list with current page and unread information
      currentPageCheck(page, unread) {
        return {
          'admin-nav-link': true,
          'current': this.$root.$data.global.currentPage === page || this.State('misc.currentPage') === page,
          'unread': unread
        };
      },
      // ** Handles mobile menu state and currentPage **
      // When force = null the menu state will toggle
      // force can also be false which will close the menu after a defined delay
      // if an item is given, the currentPage will be set to that item
      handleMenu(force, item) {
        this.$root.$data.global.currentPage = item || this.$root.$data.global.currentPage;
        if (item) App.setState('misc.currentPage', item);
        // Added delay to allow time for new component to render in the router-view
        if (force === null) {
          this.$root.$data.global.menuOpen = !this.$root.$data.global.menuOpen;
        } else {
          setTimeout(() => this.$root.$data.global.menuOpen = force, 200);
        }
      }
    },
    beforeMount() {
      // Grabs messages to determine unread state for messages button
      axios.get(`${this.$root.$data.apiUrl}/messages`)
        .then(response => {
          this.$root.$data.global.unreadMessages = response.data.data.filter(e => e.attributes.read_at == null && e.attributes.recipient_user_id == this.$root.$data.global.user.id);
        });
    },
    mounted() {
      let channel = socket.subscribe(`private-App.User.${window.Laravel.user.id}`);
      let userId = this.$root.$data.global.user.id;
      channel.bind('App\\Events\\MessageCreated', (data) => {
        let subject = data.data.attributes.subject;
          this.$root.$data.global.detailMessages[subject] = this.$root.$data.global.detailMessages[subject] ?
                this.$root.$data.global.detailMessages[subject].push(data.data) : [data.data];
          this.$root.$data.global.detailMessages[subject].sort((a, b) => a.attributes.created_at - b.attributes.created_at);
          this.$root.$data.global.unreadMessages = _.flattenDeep(this.$root.$data.global.detailMessages).filter(e => e.attributes.read_at == null && e.attributes.recipient_user_id == userId);
          this.$root.$data.global.messages = Object.values(this.$root.$data.global.detailMessages)
            .map(e => e[e.length - 1])
            .sort((a, b) => ((a.attributes.read_at == null || b.attributes.read_at == null) && (userId == a.attributes.recipient_user_id || userId == b.attributes.recipient_user_id) ? 1 : -1));
      });
    }
  };
</script>
