<template>
  <div class="nav-bar">
    <nav class="admin-nav">
      <router-link class="admin-nav-link" to="/dashboard" title="Dashboard">
        <svg class="icon icon-nav-bar"><use xlink:href="#person" /></svg>
      </router-link>
      <router-link class="admin-nav-link" to="/appointments" title="Appointments">
        <svg class="icon icon-nav-bar"><use xlink:href="#appointments" /></svg>
      </router-link>
      <a href="/logout" class="nav-bar-logout" title="Logout">
        <svg><use xlink:href="#logout"/></svg>
      </a>
      <a href="#" class="nav-bar-account">
        <svg class="harvey-mark"><use xlink:href="#harvey-mark" /></svg>
      </a>
    </nav>
  </div>
</template>

<script>
export default {}
</script>
