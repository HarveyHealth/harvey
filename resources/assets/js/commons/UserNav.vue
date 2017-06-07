<template>
  <div class="nav-bar">
    <nav class="admin-nav">
      <router-link to="/" class="nav-bar-account">
        <svg class="harvey-mark"><use xlink:href="#harvey-logo" /></svg>
      </router-link>
      <router-link class="admin-nav-link" to="/" title="Dashboard">
        <svg class="icon icon-nav-bar"><use xlink:href="#person" /></svg>
        <div class="text">Dashboard</div>
      </router-link>
      <router-link class="admin-nav-link" to="/appointments" title="Appointments">
        <svg class="icon icon-nav-bar"><use xlink:href="#appointments" /></svg>
        <div class="text">Appointments</div>
      </router-link>
      <!--
      <router-link class="admin-nav-link" to="/lab_orders" title="Lab Orders">
        <i class="fa fa-eyedropper icon icon-nav-bar"></i>
        <div class="text">Lab Orders</div>
      </router-link>
      -->
      <router-link class="admin-nav-link" to="/messages" title="Messages" :class="{unread: unread}">
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
  export default {
    data() {
      return {
        unread: false
      }
    },
    beforeMount() {
      axios.get(`/api/v1/messages`)
        .then(response => {
          let unread = response.data.data.filter(e => !e.attributes.read_at)
          this.unread = unread.length > 0 ? true : false
        })
    }
  }
</script>