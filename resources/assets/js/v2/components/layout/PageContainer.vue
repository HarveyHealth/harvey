<template>
  <div class="PageContainer relative left-0 ">
    <slot></slot>
  </div>
</template>

<script>
// PageContainer
// ----------------------------------------------------------------------------
// PageContainer is the container for all back-office content. Its main purpose
// is to manage the positioning of the container when the menu is going between
// open and unopned states.

export default {};
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
