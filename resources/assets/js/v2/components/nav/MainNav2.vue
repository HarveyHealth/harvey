<template>
    <div class="nav-wrap">
        <div class="nav-spacer"></div>
        <div class="nav-container">
            <button class="nav-hamburger">
                <i class="fa fa-bars"></i>
            </button>
            <a href="/" class="nav-logo" v-if="hasLogo">
                <LogoIcon alwaysShowText />
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
      hasStart: { type: Boolean, default: false }
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
    }

    .nav-hamburger {
        background: rgba(255,255,255,0.5);
        border: 0;
        border-radius: 50%;
        color: $color-copy;
        cursor: pointer;
        outline: none;
        padding: 12px;
        position: fixed;
        right: 12px;
        transition: background 200ms ease-in-out;
        top: 12px;
        -webkit-appearance: none;

        &:hover {
            background: rgba(255,255,255,0.9)
        }
    }

    .nav-links,
    .nav-phone,
    .nav-start {
        display: none;
    }
</style>
