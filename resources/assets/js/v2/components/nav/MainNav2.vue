<template>
    <div :class="wrapClasses">
        <div class="nav-overlay"></div>
        <div class="nav-container">
            <button class="nav-hamburger" @click="handleMenuClick()">
                <i :class="hamburgerClasses"></i>
            </button>
            <a href="/" class="nav-logo" v-if="hasLogo">
                <LogoIcon revealText />
            </a>
            <div class="nav-links" v-if="hasLinks">
                <a href="/about">About</a>
                <a href="/lab-tests">Labs</a>
                <a href="/#prices" @click="handleMenuClick('prices')">Pricing</a>
                <a href="/financing">Financing</a>
                <a href="/login">Log In</a>
            </div>
            <div class="nav-right">
                <div class="nav-phone" v-if="hasPhone">
                    <a href="tel:800-690-9989">(800) 690-9989</a>
                </div>
                <div class="nav-start" v-if="hasStart">
                    <a href="/#conditions" @click="handleMenuClick('conditions')">Get Started</a>
                </div>
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
          return `nav-wrap${this.menuIsActive ? ' menu-active' : ''}`;
      }
  },
  methods: {
      handleMenuClick(pageId) {
          if (pageId) {
              const pageSection = document.getElementById(pageId);
              if (pageSection) this.yScroll = pageSection.offsetTop;
          }

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
        margin: 0 auto;
        max-width: 1152px;
        position: relative;
        z-index: 1;
    }

    .nav-container {
        position: absolute;

        @include query-up-to(lg) {
            padding: 0 24px;
            width: 100%;

            .menu-active & {
                bottom: 0;
                overflow: auto;
                padding-bottom: 60px;
                position: fixed;
                top: 0;
            }
        }
        @include query(lg) {
            left: 24px;
            width: calc(100% - 48px);
        }

        // Hardcoded in public css
        @media screen and (min-width: 1192px) {
            left: 0;
            margin: 0 6px;
            width: 100%;
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
        background: rgba(255,255,255,0.4);
        border: 0;
        border-radius: 50%;
        color: $color-copy;
        cursor: pointer;
        height: 42px;
        outline: none;
        padding: 12px;
        position: fixed;
        right: 18px;
        transition: background 200ms ease-in-out;
        top: 12px;
        width: 42px;
        -webkit-appearance: none;

        &:hover {
            background: rgba(255,255,255,0.8)
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

    .nav-right {
        @include query(lg) {
            position: absolute;
            right: 0;
            top: 22px;
        }
    }

    .nav-links {
        @include query-up-to(lg) {
            font-size: 1.5rem;
            margin-top: 60px;
            text-align: center;
        }
        @include query(lg) {
            top: -20px;
            position: relative;
        }
        @include query(xl) {
            left: 48px;
        }
    }

    .nav-links a {
        color: white;

        @include query-up-to(lg) {
            display: block;
            padding: 1.6rem;
        }
        @include query(lg) {
            display: inline-block;
            font-size: 16px;
            font-weight: 500;
            margin: 12px 0;
            padding: 10px;

            &:hover {
                opacity: 0.7;
            }
        }
        @include query(xl) {
            font-size: 18px;
        }
    }

    .nav-phone a,
    .nav-start a {
        border: 1px solid;
        border-radius: 4px;
        color: white;
        text-align: center;

        @include query-up-to(lg) {
            bottom: 12px;
            font-size: 16px;
            padding: 12px;
            position: fixed;
            width: calc(50% - 18px);
        }
        @include query(lg) {
            font-size: 14px;
            margin-left: 12px;
            padding: 8px 10px;
        }
        @include query(xl) {
            font-size: 16px;
            padding: 10px 16px;
        }
    }

    .nav-phone a {
        border-color: white;

        @include query-up-to(lg) {
            background: $color-copy;
            left: 12px;
        }
    }

    .nav-start a {
        background: $color-accent-dark;
        border-color: $color-accent-dark;

        @include query-up-to(lg) {
            right: 12px;
        }
    }

    @include query(lg) {
        .nav-hamburger,
        .nav-overlay {
            display: none;
        }

        .nav-links,
        .nav-phone,
        .nav-start {
            display: inline-block;
        }
    }
</style>
