<template>
  <div :class="{
    'notification': true,
    [`from-${comesFrom}`]: true,
    'isactive': active
  }">
    <span
      v-if="symbol !== ''"
      class="notification-symbol"
      v-html="symbol"
    ></span>
    <span v-html="text"></span>
  </div>
</template>

<script>
export default {
  props: {
    active: {
      type: Boolean,
      required: true
    },
    comesFrom: {
      type: String,
      required: true
    },
    symbol: {
      type: String
    },
    text: {
      type: String,
      required: true
    }
  }
}
</script>
