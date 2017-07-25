<template>
  <div :class="containerClasses" v-if="!$root.$data.signup.completedSignup">
    <div class="signup-stage-instructions">
      <StagesNav :current="'billing'" />
      <h2 v-text="title"></h2>
      <p v-html="subtext"></p>
      <div class="credit-card">
        <div class="credit-card__chip"></div>
        <div class="credit-card__brand"></div>
        <div class="credit-card__number">7364 1239 6735 9755</div>
        <div class="credit-card__name">JONATHAN MCGILLICU</div>
        <div class="credit-card__date">05/20</div>
      </div>
    </div>
    <div class="signup-container signup-phone-container text-centered">
      <router-link class="signup-back-button" :to="{ name: 'schedule', path: '/schedule' }"><i class="fa fa-long-arrow-left"></i><span>Schedule</span></router-link>

      <div class="phone-input-container">
        <button class="button button--blue" style="width: 180px" :disabled="isProcessingInfo">
          <span v-if="!isProcessingInfo">Save &amp; Continue</span>
          <LoadingBubbles v-else-if="isProcessingInfo" :style="{ width: '12px', fill: 'white' }" />
          <i v-else-if="isComplete" class="fa fa-check"></i>
        </button>
      </div>

    </div>
  </div>
</template>

<script>
import LoadingBubbles from '../../../commons/LoadingBubbles.vue';
import StagesNav from '../util/StagesNav.vue';

export default {
  name: 'billing',
  components: {
    LoadingBubbles,
    StagesNav,
  },
  data() {
    return {
      code: this.$root.$data.signup.code || '',
      codeDigits: 5,
      containerClasses: {
        'anim-fade-slideup': true,
        'anim-fade-slideup-in': false,
        'container': true,
      },
      isProcessingInfo: false,
      subtext: 'Please enter a credit or debit card to save on file. We will not charge your card until after your first consultation is complete.',
      title: 'Enter Payment Method',
    }
  },
  computed: {
  },
  methods: {
  },
  mounted () {
    this.$root.toDashboard();
    this.$root.$data.signup.visistedStages.push('billing');
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', true, 300);
  },
  beforeDestroy() {
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', false);
  }
}
</script>
