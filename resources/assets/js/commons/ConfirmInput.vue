<template>
  <div class="phone-confirm-input-wrapper">
    <input ref="0" type="text"
        :disabled="disabled"
        @keydown="keyed($event, 0)"
        @input="distribute($event, 0)" />
    <input ref="1" type="text"
        :disabled="disabled"
        @keydown="keyed($event, 1)"
        @input="distribute($event, 1)" />
    <input ref="2" type="text"
        :disabled="disabled"
        @keydown="keyed($event, 2)"
        @input="distribute($event, 2)" />
    <input ref="3" type="text"
        :disabled="disabled"
        @keydown="keyed($event, 3)"
        @input="distribute($event, 3)" />
    <input ref="4" type="text"
        :disabled="disabled"
        @keydown="keyed($event, 4)"
        @input="distribute($event, 4)" />
  </div>
</template>

<script>
export default {
  props: {
    disabled: Boolean,
    getValue: {
      type: Function,
      required: true
    },
    stored: String
  },
  data() {
    return {
      inputs: []
    };
  },
  watch: {
    inputs() {
      return this.getValue(this.inputs.join(''));
    }
  },
  methods: {
    distribute(e, ref) {
      const value = typeof e === 'string' ? e : e.target.value;
      let i, j = 0, k = 0;
      for (i = ref; i < 5; i++, j++) {
        if (!this.$refs[i] || !value.charAt(j)) break;
        this.$refs[i].value = value.charAt(j);
      }
      for (; k < 5; k++) {
        Vue.set(this.inputs, k, this.$refs[k].value);
      }
      if (i < 5) {
        this.$refs[i].focus();
      } else {
        this.$refs[4].focus();
      }
    },
    keyed(e, ref) {
      const value = e.target.value;
      const forward = e.keyCode === 39;
      const backward = e.keyCode === 37;
      const backspace = e.keyCode === 8;
      const start = this.$refs[ref].selectionStart === 0;
      const end = this.$refs[ref].selectionStart === 1;
      const previous = this.$refs[ref - 1];
      const next = this.$refs[ref + 1];

      if (backspace && start && previous) {
        e.preventDefault();
        this.$refs[ref - 1].focus();
      }
      if (backward && start && previous) {
        e.preventDefault();
        this.$refs[ref - 1].focus();
        this.$refs[ref - 1].setSelectionRange(1,1);
      }
      if (forward && ((value.length && end) || !value.length) && next) {
        e.preventDefault();
        this.$refs[ref + 1].focus();
        this.$refs[ref + 1].setSelectionRange(0,0);
      }
    }
  },
  mounted() {
    if (this.stored) {
      this.distribute(this.stored, 0);
    }
  }
};
</script>
