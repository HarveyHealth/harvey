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
        };
    },
    computed: {
        currentIndex() {
            let index = 0;
            this.stages.forEach((stg, i) => {
                if (this.current === stg.name) {
                    index = i;
                }
            });
            return index;
        },
        stages() {
            let stages = [
                { name: 'practitioner',
                    path: '/practitioner' },
                { name: 'phone',
                    path: '/phone' },
                { name: 'schedule',
                    path: '/schedule' },
                { name: 'payment',
                    path: '/payment' },
                { name: 'confirmation',
                    path: '/confirmation' }
            ];
            stages = stages.filter(stage => {
                if (stage.name === 'phone' && Laravel.user.phone_verified_at) {
                    return false;
                } else {
                    return true;
                }
            });

            return stages;
        }
    }
};
</script>
