<template>
  <div :class="containerClasses" v-if="!$root.$data.signup.completedSignup">
    <div class="vertical-center">
      <div class="signup-container signup-interstitial-container font-centered">
        <div class="signup-main-icon">
          <svg class="interstitial-icon icon-rocket"><use xlink:href="#rocket" /></svg>
        </div>
        <h2 class="heading-1 font-normal">Welcome to Harvey</h2>
        <p>You will need to answer a few basic questions before you can book a consultation with a Naturopathic Doctor.</p>
        <p>This process will take 3-5 minutes. You will need to have your mobile phone ready to validate your phone number. After booking a consultation, we will ask you to fill out a more detailed client intake form for your doctor.</p>
        <button class="button button--blue" @click="$router.push('practitioner')">Let's Go!</button>
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
        'flex-wrapper': true,
        'height-100': true,
        'justify-center': true
      },
    }
  },
  mounted () {
    this.$root.toDashboard();
    this.$root.setup();

    if (Laravel.user.phone) this.$root.$data.signup.phoneConfirmed = true;
    if (Laravel.user.has_a_card) this.$root.$data.signup.billingConfirmed = true;

    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', true, 300);

    if(this.$root.shouldTrack()) {
      analytics.page('Welcome');
    }
  },
  beforeDestroy() {
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', false);
  }
}
</script>
