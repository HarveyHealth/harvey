<template>
  <div :class="containerClasses">
    <div class="signup-interstitial-container text-centered">
      <div class="signup-main-icon">
        <svg class="interstitial-icon icon-globe"><use xlink:href="#globe" /></svg>
      </div>
      <h2>We&rsquo;re sorry!</h2>
      <p>Unfortunately, we can not service clients in your state yet, but we&rsquo;re working on it. We will add you to our newsletter and let you know as soon as we launch there.</p>
      <div class="social-icon-wrapper">
        <a v-for="icon in socialIcons" :href="icon.href">
          <i :class="icon.class"></i>
        </a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'welcome',
  data() {
    return {
      containerClasses: {
        'anim-fade-slideup': true,
        'anim-fade-slideup-in': false,
        'container': true,
        'welcome-container': true,
      },
      socialIcons: [
        { class: 'fa fa-medium', href: 'https://blog.goharvey.com/' },
        { class: 'fa fa-instagram', href: 'https://www.instagram.com/goharveyapp/' },
        { class: 'fa fa-facebook', href: 'https://www.facebook.com/goharveyapp' },
        { class: 'fa fa-twitter', href: 'https://twitter.com/goharveyapp' },
        { class: 'fa fa-youtube', href: 'https://www.youtube.com/channel/UCNW4aHA1yCPUdk7OM65oNDw' },
      ]
    }
  },
  mounted () {
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', true, 300);
  },
  beforeDestroy() {
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', false);
  }
}
</script>
