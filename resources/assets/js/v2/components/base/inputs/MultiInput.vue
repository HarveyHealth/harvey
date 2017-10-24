<template>
  <div :class="classes">
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
    autoFocus: {
      type: Boolean
    },
    color: {
      type: String,
      default: 'dark'
    },
    disabled: Boolean,
    getValue: {
      type: Function,
      required: true
    },
    // { refs: $refs, ref: 'name' }
    // will auto focus ref after last input is filled in
    // takes this data structure in case the ref to be passed in
    // has not been defined yet
    focusNext: Object,
    quantity: Number,
    size: {
      type: String,
      default: 'md'
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
  computed: {
    classes() {
      return `MultiInput ${this.color} ${this.size}`;
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
        if (this.focusNext) {
          Vue.nextTick(() => this.focusNext.refs[this.focusNext.ref].$el.focus());
        } else {
          this.$refs[this.quantity][0].focus();
        }
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
    if (this.autoFocus) {
      this.$refs[1][0].focus();
    }
  }
};
</script>
