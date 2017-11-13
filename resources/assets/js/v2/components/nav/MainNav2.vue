<template>
    <div class="nav-wrap">
        <div class="nav-spacer"></div>
        <div class="nav-container">
            <a href="/" class="nav-logo">
                <LogoIcon />
            </a>
            <div class="nav-links">
                <a href="/about">About</a>
                <a href="/lab-tests">Labs</a>
                <a href="/#prices">Pricing</a>
                <a href="/financing">Financing</a>
                <a href="/login">Log In</a>
            </div>
            <div class="nav-phone">
                <a href="tel:800-690-9989">(800) 690-9989</a>
            </div>
            <div class="nav-start">
                <a href="/">Get Started</a>
            </div>
        </div>
    </div>
</template>

<script>
import { LogoIcon } from 'icons';

export default {
  components: { LogoIcon }
};
</script>

<style lang="scss" scoped>

</style>
