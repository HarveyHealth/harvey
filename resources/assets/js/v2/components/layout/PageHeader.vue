<template>
  <header class="bb b--light-gray bg-white pb4 pt5 ph3">
    <Heading1>{{ heading }}</Heading1>
  </header>
</template>

<script>
// PageHeader
// ----------------------------------------------------------------------------
// PageHeader is used for each back-office page header area. It standardizes
// padding and heading text currently. Eventually it should contain props
// for adding filters and an action button.

import { Heading1 } from 'typography';

export default {
  name: 'PageHeader',
  components: { Heading1 },
  props: {
    heading: String
  }
};
</script>

<style lang="scss" scoped>
</style>
