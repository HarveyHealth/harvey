<template>
  <p :class="styles">
      <slot></slot>
  </p>
</template>

<script>
export default {
    props: {
        color: {
            type: String,
            default: 'dark'
        },
        size: String,
        weight: {
            type: String,
            default: 'normal'
        }
    },
    computed: {
        styles() {
            return {
                'dark-gray': this.color === 'dark',
                'font-lg': this.size === 'large',
                'font-sm': this.size === 'small',
                'fw4': this.weight === 'normal',
                'fw3': this.weight === 'thin',
                'fw5': this.weight === 'bold',
                'gray': this.color === 'muted',
                'ma0': true,
                'white': this.color === 'light'
            };
        }
    }
};
</script>
