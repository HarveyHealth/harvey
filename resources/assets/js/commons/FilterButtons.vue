<template>
  <div class="filters">
    <button
      v-for="(name, index) in filters"
      :class="{'button--filter': true, 'isactive': activeFilter === index && !loading}"
      :disabled="loading"
      @click="onFilter(name, index)">{{ name }}</button>
  </div>
</template>

<script>
export default {
    props: {
        activeFilter: {
            type: Number,
            required: true,
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
            required: true,
        },
        allData: {
            type: Array,
            required: true,
        },
    },
};
</script>
