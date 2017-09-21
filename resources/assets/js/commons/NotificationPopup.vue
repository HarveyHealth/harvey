<template>
  <div :class="{
    'error': asError,
    'notification': true,
    [`from-${comesFrom}`]: true,
    'isactive': active
  }">
    <span
      v-if="symbol !== ''"
      class="notification-symbol notify-color"
      v-html="symbol"
    ></span>
    <span v-html="text" class="notify-color"></span>
  </div>
</template>

<script>
export default {
    props: {
    // Whether the popup is viewable or not
        active: {
            type: Boolean,
            required: true
        },
        // Changes the background for error indication
        asError: {
            type: Boolean
        },
        // Linked to CSS classes for various starting positions
        // and transition positions
        // available: 'top-right', 'bottom-right'
        comesFrom: {
            type: String,
            required: true
        },
        // Symbol displayed on the left of the popup
        // User HTML, like &#10003;
        symbol: {
            type: String
        },
        // The text to display in the popup
        text: {
            type: String,
            required: true
        }
    }
};
</script>
