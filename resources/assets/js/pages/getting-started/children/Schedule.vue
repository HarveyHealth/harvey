<template>
  <div :class="containerClasses">
    <div class="signup-stage-instructions">
      <StagesNav :current="'schedule'" />
      <h2>Choose date and time...</h2>
      <p>Tell us the best date and time you would like to schedule a phone consultation with your doctor. Remember, this is a <strong>virtual</strong> meeting.</p>
    </div>
    <div class="signup-container signup-stage-container signup-schedule-container">
      <router-link class="signup-back-button" :to="{ name: 'phone', path: '/phone' }"><i class="fa fa-arrow-left"></i> Phone</router-link>

      <div class="signup-schedule-wrapper cf">
        <div class="schedule-section schedule-days">
          <h3>Choose date</h3>
          <div class="schedule-week">
            <div class="schedule-week-info">
              <span class="week">This week</span>
              <span class="dates">June 26 - June 30</span>
            </div>
            <ol>
              <li>Mon</li>
              <li>Tue</li>
              <li>Wed</li>
              <li class="available">Thu</li>
              <li class="selected">Fri</li>
              <li>Sat</li>
              <li>Sun</li>
            </ol>
          </div>
        </div>
        <div class="schedule-section schedule-times">
          <h3>Choose time</h3>
          <ol>
            <li class="available">9a</li>
            <li class="available">10a</li>
            <li class="available">10:30a</li>
            <li class="available">11p</li>
            <li class="available">11:30p</li>
          </ol>
          <p class="schedule-timezone"></p>
        </div>
      </div>

      <p class="text-centered">Please note, all times are listed in PST. Please allow 60 minutes or longer for appointments.</p>

      <button class="button button--blue" style="width: 160px" :disabled="processing">
        <span v-if="!processing">Continue</span>
        <LoadingBubbles v-else-if="processing" :style="{ width: '16px', fill: 'white' }" />
      </button>

    </div>
  </div>
</template>

<script>
import LoadingBubbles from '../../../commons/LoadingBubbles.vue';
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
      processing: false,
    }
  },
  methods: {

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
