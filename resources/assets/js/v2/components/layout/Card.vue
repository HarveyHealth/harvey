<template>
  <div class="ba b--light-gray bg-white">
    <div class="Heading bb b--light-gray pa3" v-if="heading">
      <Heading3>{{ heading }}</Heading3>
    </div>
    <slot></slot>
  </div>
</template>

<script>
// Card
// ---------------------------------------------------------------------------
// Card layout with a white background, light gray border, and specified padding.
// The heading prop takes a string that is injected into a Heading3 component
// with a light gray background and similar padding. A nameless <slot>
// is provided for children which should be CardContent to keep padding
// uniform
import { Heading3 } from 'typography';

export default {
  components: { Heading3 },
  props: {
    heading: String
  }
};
</script>

<style lang="scss" scoped>
    @import '~sass';

    .Heading {
        background-color: $color-gray-0;
    }
</style>
