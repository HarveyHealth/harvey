<template>
  <div class="header nav is-inverted">
    <div class="container">
      <div class="nav-left">
        <a href="/" class="nav-item">
          <div class="logo-wrapper">
            <SvgIcon class="MainNav_Logo" :id="'harvey-logo'" />
          </div>
        </a>
        <div class="nav-items thinner">
          <a href="/about" class="link">About</a>
          <a href="/lab-tests" class="link is-hidden-mobile">Labs</a>
          <a href="/#prices" class="link is-hidden-mobile is-hidden-tablet-only">Pricing</a>
          <a href="/financing" class="link is-hidden-mobile is-hidden-tablet-only">Financing</a>
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

<style scoped>
  @media screen and (max-width: 767px) {
    .is-hidden-mobile {
      display: none !important;
    }
  }
  @media screen and (max-width: 999px) and (min-width: 768px) {
    .is-hidden-tablet-only {
      display: none !important;
    }
  }
  @media screen and (max-width: 999px) {
    .header .container .logo-wrapper {
      max-width: none;
    }
  }
  .nav {
    -webkit-box-align: stretch;
    -ms-flex-align: stretch;
    align-items: stretch;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    min-height: 3.5rem;
    position: relative;
    text-align: center;
    z-index: 2;
  }
  .nav > .container {
    -webkit-box-align: stretch;
    -ms-flex-align: stretch;
    align-items: stretch;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    min-height: 3.5rem;
    width: 100%;
  }
  .header .container .nav-items {
    padding: 0.75em 0.5em;
  }
  .nav-left,
  .nav-right {
    -webkit-box-align: stretch;
    -ms-flex-align: stretch;
    align-items: stretch;
    -ms-flex-preferred-size: 0;
    flex-basis: 0;
    -webkit-box-flex: 1;
    -ms-flex-positive: 1;
    flex-grow: 1;
    -ms-flex-negative: 0;
    flex-shrink: 0;
  }
  .nav-left {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: start;
    -ms-flex-pack: start;
    justify-content: flex-start;
    overflow: hidden;
    overflow-x: auto;
    white-space: nowrap;
  }
  @media screen and (max-width: 767px) {
    .nav-left {
      -ms-flex-preferred-size: auto;
      flex-basis: auto;
    }
  }
  .nav-right {
    -webkit-box-pack: end;
    -ms-flex-pack: end;
    justify-content: flex-end;
  }
  .nav-item {
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    flex-grow: 0;
    flex-shrink: 0;
    font-size: 1rem;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
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
