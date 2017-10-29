<template>
  <div class="ba b--light-gray bg-white">
    <div class="bb b--light-gray bg-gray-0 pa3" v-if="heading">
      <Heading3>{{ heading }}</Heading3>
    </div>
    <slot></slot>
  </div>
</template>

<script>
import { Heading3 } from 'typography';

export default {
  components: { Heading3 },
  props: {
    heading: String
  }
}
</script>
