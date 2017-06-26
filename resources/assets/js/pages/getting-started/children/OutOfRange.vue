<template>
  <div :class="containerClasses">
    <div class="signup-interstitial-container text-centered">
      <div class="signup-main-icon">
        <svg class="interstitial-icon icon-globe"><use xlink:href="#globe" /></svg>
      </div>
      <h2>We&rsquo;re sorry!</h2>
      <p>Unfortunately, we can not service clients in your state yet, but we&rsquo;re working on it. We will add you to our newsletter and let you know as soon as we launch there.</p>
      <button class="button button--blue" @click="$router.push('practitioner')">Continue</button>
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
