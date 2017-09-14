<template>
  <ClipLoader :color="colors[color]" :size="sizes[size]" />
</template>

<script>
import { ClipLoader } from 'vue-spinner/dist/vue-spinner.min.js';
export default {
  props: ['color', 'size'],
  components: { ClipLoader },
  data() {
    return {
      colors: {
        dark: this.Config.misc.colors.copy,
        light: this.Config.misc.colors.white,
        muted: this.Config.misc.colors.muted
      },
      sizes: {
        sm: '12px',
        md: '22px',
        lg: '34px',
      }
    }
  }
}
</script>
