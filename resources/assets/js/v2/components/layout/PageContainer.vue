<template>
  <div class="PageContainer relative left-0 ">
    <slot></slot>
  </div>
</template>

<script>
export default {}
</script>

<style lang="scss" scoped>
  @import '~sass';

  .PageContainer {
    flex-grow: 1;
    transition: left 200ms ease-in-out, margin-left 200ms ease-in-out;

    .menu-open & {
      left: 150px;
    }

    @include query(xl) {
      margin-left: 150px;
      left: 0px !important;
    }
  }
</style>
