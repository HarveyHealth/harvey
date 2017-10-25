<template>
  <ClipLoader :color="colors[color]" :size="sizes[size]" />
</template>

<script>
import { ClipLoader } from 'vue-spinner/dist/vue-spinner.min.js';

// Standard loading graphic with adjustable color and size
export default {
  props: {
    color: {
      type: String,
      default: 'muted'
    },
    size: {
      type: String,
      default: 'md'
    }
  },
  components: {
    ClipLoader
  },
  data() {
    return {
      colors: {
        dark: this.Config.misc.colors.copy,
        light: this.Config.misc.colors.white,
        muted: this.Config.misc.colors.muted
      },
      sizes: {
        sm: '10px',
        md: '22px',
        lg: '34px'
      }
    };
  }
};
</script>
