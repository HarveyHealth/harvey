<template>
  <div>
    <button
      v-for="(obj, index) in filterdata"
      :class="{'button--filter': true, 'isactive': activeFilter === index}"
      @click="handleFilter(index, obj)">{{ obj.name }}</button>
  </div>
</template>

<script>
export default {
  props: ['filterdata'],
  data() {
    return {
      activeFilter: 0
    }
  },
  methods: {
    handleFilter(index, obj) {
      this.activeFilter = index;
      this.$eventHub.$emit(obj.event);
    }
  }
}
</script>
