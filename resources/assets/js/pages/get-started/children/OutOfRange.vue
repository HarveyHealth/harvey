<template>
  <div :class="containerClasses" v-if="!$root.$data.signup.completedSignup">
    <div class="vertical-center tc">
      <div class="signup-container small naked">
        <router-link class="signup-back-button" :to="{ name: 'welcome', path: 'welcome' }">
          <i class="fa fa-long-arrow-left"></i>
        </router-link>
        <div class="signup-main-icon">
          <svg class="interstitial-icon icon-globe"><use xlink:href="#globe" /></svg>
        </div>
        <h2 class="heading-1 font-normal">We&rsquo;re sorry!</h2>
        <p>Unfortunately, we are unable to service clients in your state yet, but we are recruiting doctors quickly. Subscribe to our newsletter and we will notify you when we launch in your state.</p>
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
      }
    };
  },
  mounted () {
    this.$root.toDashboard();
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', true, 300);
    analytics.page('Out of Range');
    analytics.track('Out of Range');
    analytics.identify();
  },
  beforeDestroy() {
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', false);
  }
};
</script>
