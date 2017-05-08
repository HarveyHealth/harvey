<template>
  <table>
    <thead>
      <tr>
        <th v-for="col in tableColumns">{{ col }}</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="rowData in tableData">
        <td v-for="value, col in rowData">{{ value }}</td>
      </tr>
    </tbody>
  </table>
</template>

<script>
export default {
  props: ['columns', 'compdata'],
  computed: {
    tableData() {
      return this.compdata || [];
    },
    tableColumns() {
      return this.compdata ? Object.keys(this.compdata[0]) : [];
    }
  }
}
</script>
