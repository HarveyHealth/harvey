<template>
  <div :class="classes">
    <h1 class="heading-1" v-html="heading"></h1>
  </div>
</template>

<script>
export default {
  computed: {
    classes() {
      return {
        MainHeader: this.type === 'main'
      }
    }
  },
  props: {
    actions: {
      type: Array
    },
    filters: {
      type: Array,
    },
    heading: {
      type: String,
      required: true,
    },
    type: {
      type: String,
      default: 'main'
    }
  }
}
</script>
