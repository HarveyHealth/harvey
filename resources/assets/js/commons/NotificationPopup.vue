<template>
  <div :class="classes">
    <span
      v-if="symbol !== ''"
      class="notification-symbol"
      v-html="symbol"></span>
    {{ text }}
  </div>
</template>

<script>
export default {
  props: ['symbol', 'from'],
  data() {
    return {
      duration: 3000,
      text: '',
      classes: {
        'notification': true,
        [`from-${this.from}`]: true,
        'isactive': false
      }
    }
  },
  mounted() {
    this.$eventHub.$on('eventCallNotificationPopup', msg => {
      this.text = msg;
      this.classes.isactive = true;
      setTimeout(() => this.classes.isactive = false, this.duration);
    })
  },
  destroyed() {
    this.$eventHub.$off('eventCallNotificationPopup');
  }
}
</script>
