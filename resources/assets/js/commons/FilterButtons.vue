<template>
  <div class="filters">
    <button
      v-for="(name, index) in filters"
      :class="{'button--filter': true, 'isactive': activeFilter === index && !loading}"
      :disabled="loading"
      @click="handleFilter(name, index)">
      {{ name.name ? name.name : name }}
      <div class="filter-bubble" v-if="name.count">{{ name.count }}</div>
      </button>
  </div>
</template>

<script>
export default {
  props: {
    activeFilter: {
      type: Number,
      required: true
    },
    filters: {
      type: Array,
      required: true,
    },
    loading: {
      type: Boolean,
    },
    onFilter: {
      type: Function,
      required: true
    },
    allData: {
      type: Array,
      required: true
    },
    flyout: {
      type: Function
    }
  },
  methods: {
    handleFilter(name, index) {
      this.onFilter(name, index);
      if (this.flyout) this.flyout();
    }
  }
}
</script>
