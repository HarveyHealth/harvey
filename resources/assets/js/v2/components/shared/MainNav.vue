<template>
  <nav :class="classes">
    <div class="MainNav-Wrapper">
      <a href="/">Home</a>
      <a href="/about">About</a>
      <a href="/lab-tests">Lab Tests</a>
      <a href="help.goharvey.com">Help</a>
      <a href="/login">Login</a>
    </div>
    <button class="menu" @click="toggleMenu">
      <i :class="menuClass"></i>
    </button>
  </nav>
</template>

<script>
export default {
  props: {
    context: String
  },
  data() {
    return {
      isOpen: false,
    }
  },
  computed: {
    classes() {
      return `MainNav-${this.context} ${this.isOpen ? 'is-open' : ''}`;
    },
    menuClass() {
      return `fa ${this.isOpen ? 'fa-close' : 'fa-bars'}`;
    }
  },
  methods: {
    toggleMenu() {
      return this.isOpen = !this.isOpen;
    }
  }
}
</script>
