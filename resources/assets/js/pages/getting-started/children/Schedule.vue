<template>
  <div :class="containerClasses">
    <div class="signup-stage-instructions">
      <StagesNav :current="'schedule'" />
      <h2>Choose date and time...</h2>
      <p>Tell us the best date and time you would like to schedule a phone consultation with your doctor. Remember, this is a <strong>virtual</strong> meeting.</p>
    </div>
    <div class="signup-container signup-stage-container">
      <router-link class="signup-back-button" :to="{ name: 'phone', path: '/phone' }"><i class="fa fa-arrow-left"></i> Phone</router-link>
    </div>
  </div>
</template>

<script>
import StagesNav from '../util/StagesNav.vue';

export default {
  name: 'schedule',
  components: {
    StagesNav
  },
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
    this.$root.$data.signup.visistedStages.push('schedule');
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', true, 300);
  },
  beforeDestroy() {
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', false);
  }
}
</script>
