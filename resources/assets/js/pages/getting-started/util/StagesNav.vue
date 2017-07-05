<template>
  <div class="signup-stages-nav">
    <template v-for="(stage, index) in stages">
      <template v-if="$root.$data.signup.visistedStages.indexOf(stage.name) > -1">
        <span class="stage-nav-link current-stage" v-if="index === currentIndex"></span>
        <router-link class="stage-nav-link" v-else :to="stage" />
      </template>
      <span class="stage-nav-link" v-else></span>
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
    },
    stages() {
      if (this.$root.$data.global.user.attributes) {
        const stages = [
          { name: 'practitioner',
            path: '/practitioner' },
          { name: 'phone',
            path: '/phone' },
          { name: 'schedule',
            path: '/schedule' },
          { name: 'confirmation',
            path: '/confirmation' },
        ];
        if (this.$root.$data.global.user.attributes.phone) {
          stages.splice(1,1);
        }
        return stages;
      } else {
        return [];
      }
    }
  }
}
</script>
