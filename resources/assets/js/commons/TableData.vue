<template>
  <table class="tabledata" cellpadding="0" cellspacing="0">
    <thead>
      <tr>
        <th
          v-for="obj in columns"
          @click="utilSort(obj)"
          :width="obj.width"
          :class="{
            sorted: sortedColumn === obj.key,
            'sort-up': sortAscending,
            'sort-down': !sortAscending
          }"
        >{{ obj.name }}</th>
      </tr>
    </thead>
    <tbody>
      <tr
        v-for="(obj, i) in tabledata"
        @click="handleRowClick(obj, i)"
        :class="{ selected:selectedIndex === i }"
      >
        <td v-for="val in obj.rowValues">{{ val }}</td>
      </tr>
    </tbody>
  </table>
</template>

<script>
export default {
  props: ['columns', 'tabledata'],

  data() {
    return {
      defaultSortColumn: null,
      selectedIndex: null,
      selectedObject: null,
      sortedColumn: null,
      sortAscending: true,
    }
  },

  computed: {

  },

  methods: {
    handleRowClick(obj, index) {
      if (this.selectedIndex !== index) {
        this.selectedIndex = index;
        this.selectedObject = this.tabledata[index];
        this.$eventHub.$emit('eventRowClick', obj, index);
      } else {
        this.selectedIndex = null;
        this.selectedObject = null;
        this.$eventHub.$emit('eventRowClick', null, null);
      }
    },
    utilSort(obj) {
      if (obj.sort) {
        if (this.selectedObject) {
          this.selectedIndex = null;
        }
        if (this.sortedColumn === obj.key) {
          this.tabledata.reverse();
          this.sortAscending = !this.sortAscending;
        } else {
          this.tableData = this.tableData.sort(obj.sort);
          this.sortedColumn = obj.key;
          this.sortAscending = true;
        }
        this.tabledata.forEach((obj, i) => {
          if (this.selectedObject === obj) {
            this.selectedIndex = i;
            return false;
          }
        })
      }
    },
  },
  mounted() {
    this.columns.forEach(obj => {
      if (this.defaultSortColumn === obj.key) {
        this.utilSort(obj);
      }
    })
  },
}
</script>
