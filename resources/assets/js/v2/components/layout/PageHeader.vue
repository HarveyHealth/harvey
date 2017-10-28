<template>
  <header class="bb b--light-gray bg-white pb4 pt5 ph3">
    <Heading1>{{ heading }}</Heading1>
  </header>
</template>

<script>
import { Heading1 } from 'typography';

export default {
  name: 'PageHeader',
  components: { Heading1 },
  props: {
    heading: String
  }
}
</script>

<style lang="scss" scoped>
</style>
