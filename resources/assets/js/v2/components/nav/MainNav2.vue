<template>
    <div :class="wrapClasses">
        <div class="nav-spacer"></div>
        <div class="nav-overlay"></div>
        <div class="nav-container">
            <button class="nav-hamburger" @click="handleMenuClick">
                <i :class="hamburgerClasses"></i>
            </button>
            <a href="/" class="nav-logo" v-if="hasLogo">
                <LogoIcon revealText />
            </a>
            <div class="nav-links" v-if="hasLinks">
                <a href="/about">About</a>
                <a href="/lab-tests">Labs</a>
                <a href="/#prices">Pricing</a>
                <a href="/financing">Financing</a>
                <a href="/login">Log In</a>
            </div>
            <div class="nav-phone" v-if="hasPhone">
                <a href="tel:800-690-9989">(800) 690-9989</a>
            </div>
            <div class="nav-start" v-if="hasStart">
                <a href="/">Get Started</a>
            </div>
        </div>
    </div>
</template>

<script>
import { LogoIcon } from 'icons';

export default {
  components: { LogoIcon },
  props: {
      hasLinks: { type: Boolean, default: false },
      hasLogo: { type: Boolean, default: false },
      hasPhone: { type: Boolean, default: false },
      hasStart: { type: Boolean, default: false },
      onMenuClick: { type: Function, required: true }
  },
  data() {
    return {
        menuIsActive: false,
        yScroll: null
    }
  },
  computed: {
      hamburgerClasses() {
          return `fa ${this.menuIsActive ? 'fa-close' : 'fa-bars'}`;
      },
      wrapClasses() {
          return `nav-wrap ${this.menuIsActive && 'menu-active'}`;
      }
  },
  methods: {
      handleMenuClick() {
          if (this.menuIsActive) {
              this.onMenuClick();
              Vue.nextTick(() => {
                  window.scroll(0, this.yScroll);
              });
          } else {
              this.yScroll = window.scrollY;
              setTimeout(this.onMenuClick, 200);
          }
          this.menuIsActive = !this.menuIsActive;
      }
  }
};
</script>

<style lang="scss" scoped>
    @import '~sass';

    .nav-wrap {
        position: relative;
        z-index: 1;
    }

    .nav-container {
        padding: 0 12px;
        position: absolute;
        width: 100%;

        .menu-active & {
            bottom: 0;
            overflow: auto;
            padding-bottom: 60px;
            position: fixed;
            top: 0;
        }
    }

    .nav-overlay {
        background: rgba($color-copy, 0);
        transition: background 200ms ease-in-out;

        .menu-active & {
            background: rgba($color-copy, 0.97);
            background-size: cover;
            background-position: center;
            bottom: 0;
            left: 0;
            position: fixed;
            top: 0;
            right: 0;
        }
    }

    .menu-active .logo {
        position: fixed;
    }

    .nav-hamburger {
        background: rgba(255,255,255,0.5);
        border: 0;
        border-radius: 50%;
        color: $color-copy;
        cursor: pointer;
        height: 42px;
        outline: none;
        padding: 12px;
        position: fixed;
        right: 12px;
        transition: background 200ms ease-in-out;
        top: 12px;
        width: 42px;
        -webkit-appearance: none;

        &:hover {
            background: rgba(255,255,255,0.9)
        }

        .fa {
            font-size: 1rem;
        }

        .menu-active & {
            background: transparent;
            color: white;
        }
    }

    .nav-links,
    .nav-phone,
    .nav-start {
        display: none;

        .menu-active & {
            display: block;
        }
    }

    .nav-links {
        font-size: 1.5rem;
        margin-top: 60px;
        text-align: center;

        a {
            color: white;
            display: block;
            padding: 1.5rem;
        }
    }

    .nav-phone a,
    .nav-start a {
        bottom: 12px;
        border: 1px solid;
        border-radius: 4px;
        color: white;
        padding: 12px;
        position: fixed;
        text-align: center;
        width: calc(50% - 18px);
    }

    .nav-phone a {
        background: $color-copy;
        border-color: white;
        left: 12px;
    }

    .nav-start a {
        background: $color-accent-dark;
        border-color: $color-accent-dark;
        right: 12px;
    }
</style>
