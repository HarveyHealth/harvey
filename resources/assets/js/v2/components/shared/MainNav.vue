<template>
  <div class="header nav is-inverted">
    <div class="container">
      <div class="nav-left">
        <a href="/" class="nav-item">
          <div class="logo-wrapper">
            <SvgIcon class="MainNav_Logo" :id="'harvey-logo'" />
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

<style scoped>
  @media screen and (max-width: 767px) {
    .is-hidden-mobile {
      display: none !important;
    }
  }
  .MainNav_Logo * {
    fill: #fff!important;
  }
  .nav {
    align-items: stretch;
    display: flex;
    min-height: 3.5rem;
    position: relative;
    text-align: center;
    z-index: 2;
  }
  .nav > .container {
    align-items: stretch;
    display: flex;
    min-height: 3.5rem;
    width: 100%;
  }
  .header .container .nav-items {
    padding: 0.75em 0.5em;
  }
  .nav-left,
  .nav-right {
    align-items: stretch;
    flex-basis: 0;
    flex-grow: 1;
    flex-shrink: 0;
  }
  .nav-left {
    display: flex;
    justify-content: flex-start;
    overflow: hidden;
    overflow-x: auto;
    white-space: nowrap;
  }
  .nav-right {
    justify-content: flex-end;
  }
  .nav-item {
    align-items: center;
    display: flex;
    flex-grow: 0;
    flex-shrink: 0;
    font-size: 1rem;
    justify-content: center;
    line-height: 1.5;
    padding: 0.5rem 0.75rem;
  }
  .button.is-outlined {
    border-width: 1px;
    font-weight: normal;
    height: 2.285em;
    line-height: 1.5;
  }

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
