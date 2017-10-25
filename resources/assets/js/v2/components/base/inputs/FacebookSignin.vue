<template>
  <ButtonInput class="Button--facebook" :text="config[type]" :on-click="handleClick" :width="'240px'" />
</template>

<script>
import ButtonInput from './ButtonInput.vue';

export default {
  props: {
    onClick: Function,
    type: {
      type: String,
      required: true
    }
  },
  components: {
    ButtonInput
  },
  data() {
    return {
      config: {
        login: '<i class="fa fa-facebook"></i><span>Login With Facebook</span>',
        signup: '<i class="fa fa-facebook"></i><span>Sign In With Facebook</span>'
      }
    };
  },
  methods: {
    handleClick(e) {
      this.onClick(e);
    }
  }
};
</script>
