<template>
  <svg style="width:100%"><use :xlink:href="'#' + id" /></svg>
</template>

<script>
export default {
  props: {
    id: String
  }
}
</script>
