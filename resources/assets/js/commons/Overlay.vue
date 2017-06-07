<template>
  <div @click="onClick" :class="{ overlay:true, isactive: active }"></div>
</template>

<script>
export default {
  props: {
    active: Boolean,
    onClick: Function
  },
}
</script>
