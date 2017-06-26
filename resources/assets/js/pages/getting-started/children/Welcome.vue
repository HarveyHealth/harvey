<template>
  <div :class="containerClasses">
    <div class="signup-welcome-container text-centered">
      <p>image</p>
      <p>You will need to answer a few questions to book a consultation with one of our Naturopathic doctors.</p>
      <p>This process will take about 5 minutes and you will need to have your mobile phone handy.</p>
      <button class="button button--blue">Continue</button>
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
