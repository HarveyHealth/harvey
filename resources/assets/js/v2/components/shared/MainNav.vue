<template>
  <div class="header nav is-inverted">
    <div class="container">
      <div class="nav-left">
        <a href="/" class="nav-item">
          <div class="logo-wrapper">
            <!-- <SvgIcon class="MainNav_Logo"/> -->
          </div>
        </a>
        <div class="nav-items">
          <a href="/about" class="link">About</a>
          <a href="/lab-tests" class="link is-hidden-mobile">Labs</a>
          <a href="/#prices" class="link is-hidden-mobile is-hidden-tablet-only">Pricing</a>
          <a href="/login" class="link">Login</a>
        </div>
      </div>
      <div class="nav-right">
        <div class="nav-items">
          <a href="tel:800-690-9989" class="button is-primary is-outlined is-hidden-mobile">(800) 690-9989</a>
        </div>
      </div>
    </div>
  </div>
</template>

<style>
  .header { background: black;}
</style>

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
      return `MainNav${this.context ? '--' + this.context : ''} ${this.isOpen ? 'is-open' : ''}`;
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
