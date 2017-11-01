<template>
  <div :class="containerClasses" v-if="!$root.$data.signup.completedSignup">
    <div class="vertical-center">
      <div class="signup-container small naked">
        <router-link class="signup-back-button" :to="{ name: 'sign-up', path: 'signup' }">
          <i class="fa fa-long-arrow-left"></i>
        </router-link>
        <div class="signup-main-icon">
          <svg class="interstitial-icon icon-globe"><use xlink:href="#globe" /></svg>
        </div>
        <h2 class="heading-1 font-normal">We&rsquo;re sorry!</h2>
        <p>Unfortunately, we are unnable to service clients in your state yet, but we&rsquo;re working on it. We will add you to our newsletter and let you know as soon as we launch there.</p>
        <div class="social-icon-wrapper">
          <a v-for="icon in socialIcons" :href="icon.href">
            <i :class="icon.class"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'out-of-range',
  data() {
    return {
      containerClasses: {
        'anim-fade-slideup': true,
        'anim-fade-slideup-in': false,
        'container': true,
        'flex-wrapper': true,
        'height-100': true,
        'justify-center': true
      },
      socialIcons: [
        { class: 'fa fa-medium', href: 'https://www.goharvey.com/blog' },
        { class: 'fa fa-instagram', href: 'https://www.instagram.com/go_harvey' },
        { class: 'fa fa-facebook', href: 'https://www.facebook.com/goharveyapp' },
        { class: 'fa fa-twitter', href: 'https://twitter.com/goharveyapp' },
        { class: 'fa fa-youtube', href: 'https://www.youtube.com/channel/UCNW4aHA1yCPUdk7OM65oNDw' }
      ]
    };
  },
  mounted () {
    this.$root.toDashboard();
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', true, 300);

    if(this.$root.shouldTrack()) {
      analytics.page('Out of Range');
      analytics.track('Out of Range');
      analytics.identify();
    }
  },
  beforeDestroy() {
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', false);
  }
};
</script>
