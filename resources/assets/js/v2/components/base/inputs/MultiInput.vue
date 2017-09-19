<template>
  <div>
    <input v-for="i in quantity"
           :ref="i" type="text"
           :disabled="disabled"
           @keydown="keyed($event, i)"
           @input="distribute($event, i)"
    />
  </div>
</template>

<script>
export default {
  props: {
    disabled: Boolean,
    getValue: {
      type: Function,
      required: true,
    },
    quantity: Number,
    stored: String
  },
  data() {
    return {
      inputs: []
    }
  },
  watch: {
    inputs() {
      return this.getValue(this.inputs.join(''));
    }
  },
  methods: {
    distribute(e, ref) {
      const value = typeof e === 'string' ? e : e.target.value;
      let i, j = 0, k = 1;
      for (i = ref; i <= this.quantity; i++, j++) {
        if (!this.$refs[i] || !value.charAt(j)) break;
        this.$refs[i][0].value = value.charAt(j);
      }
      for (; k <= this.quantity; k++) {
        Vue.set(this.inputs, k, this.$refs[k][0].value);
      }
      if (i <= this.quantity) {
        this.$refs[i][0].focus();
      } else {
        this.$refs[this.quantity][0].focus();
      }
    },
    keyed(e, ref) {
      const value = e.target.value;
      const forward = e.keyCode === 39;
      const backward = e.keyCode === 37;
      const backspace = e.keyCode === 8;
      const start = this.$refs[ref][0].selectionStart === 0;
      const end = this.$refs[ref][0].selectionStart === 1;
      const previous = this.$refs[ref - 1];
      const next = this.$refs[ref + 1];

      if (backspace && start && previous) {
        e.preventDefault();
        this.$refs[ref - 1][0].focus();
      }
      if (backward && start && previous) {
        e.preventDefault();
        this.$refs[ref - 1][0].focus();
        this.$refs[ref - 1][0].setSelectionRange(1,1);
      }
      if (forward && ((value.length && end) || !value.length) && next) {
        e.preventDefault();
        this.$refs[ref + 1][0].focus();
        this.$refs[ref + 1][0].setSelectionRange(0,0);
      }
    }
  },
  mounted() {
    if (this.stored) {
      this.distribute(this.stored, 0);
    }
  }
}
</script>
