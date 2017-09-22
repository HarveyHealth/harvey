<template>
  <ButtonInput :text="config[type]" :on-click="handleClick" :width="'240px'" />
</template>

<script>
import ButtonInput from './ButtonInput';

export default {
  props: {
    type: String,
    required: true,
  },
  components: {
    ButtonInput,
  },
  data() {
    return {
      config: {
        login: 'Login With Facebook'
      }
    }
  },
  methods: {
    handleClick(e) {
      e.preventDefault();
      if(this.type === 'signup' && !this.State('getstarted.userPost.terms')) {
        this.errors.add('terms', 'error', 'required');
        this.errors.first('terms:required');
        return;
      }
      if (this.type === 'login') {
        window.location.href = '/auth/facebook';
      } else {
        window.location.href = `/auth/facebook?zip=${this.State('getstarted.userPost.zip')}`;
      }
    }
  }
}
</script>
