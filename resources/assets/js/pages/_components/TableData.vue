<template>
  <table class="tabledata" cellpadding="0" cellspacing="0">
    <thead>
      <tr>
        <th v-for="col in tableColumns">{{ col }}</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="rowData in tableData" @click="rowClick(rowData.raw)">
        <td v-for="obj, col in rowData.formatted" :width="obj.width">{{ obj.value }}</td>
      </tr>
    </tbody>
  </table>
</template>

<script>
export default {
  props: ['compdata', 'rowClickEvent'],
  computed: {
    tableData() {
      return this.compdata || [];
    },
    tableColumns() {
      return this.compdata[0] ? Object.keys(this.compdata[0].formatted) : [];
    }
  },
  methods: {
    rowClick(rowData) {
      console.log(rowData);
    }
  }
}
</script>
