<template>
    <div class="main-header">
        <div class="container container-backoffice">
            <h1 class="heading-1">
              <span class="text">{{ heading }}</span>
              <slot></slot>
            </h1>
        </div>
    </div>
</template>

<script>
// PageHeader
// ----------------------------------------------------------------------------
// PageHeader is used for each back-office page header area. It standardizes
// padding and heading text currently. Eventually it should contain props
// for adding filters and an action button.

export default {
  name: 'PageHeader',
  props: {
    heading: String
  }
};
</script>
