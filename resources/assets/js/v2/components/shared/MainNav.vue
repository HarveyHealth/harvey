<template>
  <nav :class="classes">
    <div class="MainNav-Wrapper" v-if="showMenu">
      <a href="/">Home</a>
      <a href="/about">About</a>
      <a href="/lab-tests">Lab Tests</a>
      <a href="help.goharvey.com">Help</a>
      <a href="/login">Login</a>
    </div>
    <button class="menu" @click="toggleMenu" v-if="showMenu">
      <i :class="menuClass"></i>
    </button>
    <a v-if="!showMenu" href="/">
      <SvgIcon class="MainNav-logo" :id="'harvey-logo-white'" />
    </a>
  </nav>
</template>

<script>
import { Util } from '../base';

export default {
  components: {
    SvgIcon: Util.SvgIcon
  },
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
      return `MainNav${this.context ? '-' + this.context : ''} ${this.isOpen ? 'is-open' : ''}`;
    },
    menuClass() {
      return `fa ${this.isOpen ? 'fa-close' : 'fa-bars'}`;
    },
    showMenu () {
      return this.context === 'conditions'
    }
  },
  methods: {
    toggleMenu() {
      return this.isOpen = !this.isOpen;
    }
  }
}
</script>
