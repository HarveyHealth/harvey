<template>
  <div :class="classes">
    <span class="notification-symbol" v-html="symbol"></span>	{{ text }}
  </div>
</template>

<script>
export default {
  props: ['symbol', 'text', 'from'],
  data() {
    return {
      classes: {
        'notification': true,
        [`from-${this.from}`]: true,
        'isactive': false
      }
    }
  },
  mounted() {
    this.$eventHub.$on('eventCallNotificationPopup', () => {
      this.classes.isactive = true;
      setTimeout(() => this.classes.isactive = false, 3000);
    })
  },
  destroyed() {
    this.$eventHub.$off('eventCallNotificationPopup');
  }
}
</script>
