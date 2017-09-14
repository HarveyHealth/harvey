<template>
  <div class="Card">
    <div class="Card-Header">
      <h2 class="heading-2">{{ heading }}</h2>
    </div>
    <div v-if="subHeading" class="Card-Content border-bottom align-children-middle">
      <img class="Avatar-sm margin-right-xs" :src="subHeading.avatar" />
      <h3 class="heading-3-expand">{{ subHeading.title }}</h3>
    </div>
    <slot></slot>
  </div>
</template>

<script>
export default {
  props: {
    heading: {
      type: String,
      required: true
    },
    subHeading: {
      type: Object
    }
  }
}
</script>
