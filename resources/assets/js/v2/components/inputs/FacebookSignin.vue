<template>
    <InputButton
        class="Button--facebook"
        :text="config[type]"
        :onClick="handleClick"
        :width="'240px'"
        isDisabled
    />
</template>

<script>
import { InputButton } from 'inputs';

export default {
  props: {
    onClick: Function,
    type: {
      type: String,
      required: true
    }
  },
  components: {
    InputButton
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
    handleClick() {
      this.onClick();
    }
  }
};
</script>
