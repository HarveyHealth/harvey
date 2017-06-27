<template>
  <div class="signup-stages-nav">
    <template v-for="(stage, index) in stages">
      <router-link class="stage-nav-link" v-if="index < currentIndex" :to="stage" />
      <span :class="{ 'stage-nav-link': true, 'current-stage': index === currentIndex }" v-else></span>
    </template>
  </div>
</template>

<script>
export default {
  props: {
    current: String,
    required: true
  },
  data() {
    return {
      stages: [
        { name: 'practitioner',
          path: '/practitioner' },
        { name: 'phone',
          path: '/phone' },
        { name: 'schedule',
          path: '/schedule' },
        { name: 'confirmation',
          path: '/confirmation' },
      ]
    }
  },
  computed: {
    currentIndex() {
      let index = 0;
      this.stages.forEach((stg, i) => {
        if (this.current === stg.name) {
          index = i;
        }
      })
      return index;
    }
  }
}
</script>
