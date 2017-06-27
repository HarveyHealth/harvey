<template>
  <div :class="containerClasses">
    <div class="signup-container signup-interstitial-container text-centered">
      <div class="signup-main-icon">
        <svg class="interstitial-icon icon-rocket"><use xlink:href="#rocket" /></svg>
      </div>
      <p>You will need to answer a few questions to book a consultation with one of our Naturopathic doctors.</p>
      <p>This process will take about 5 minutes and you will need to have your mobile phone handy.</p>
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
