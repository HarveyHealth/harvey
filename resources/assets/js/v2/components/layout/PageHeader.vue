<template>
  <header class="bb b--light-gray bg-white pa3 ph3">
    <Heading2>{{ heading }}</Heading2>
  </header>
</template>

<script>
// PageHeader
// ----------------------------------------------------------------------------
// PageHeader is used for each back-office page header area. It standardizes
// padding and heading text currently. Eventually it should contain props
// for adding filters and an action button.

import { Heading2 } from 'typography';

export default {
  name: 'PageHeader',
  components: { Heading2 },
  props: {
    heading: String
  }
};
</script>

<style lang="scss" scoped>
</style>
