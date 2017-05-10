<template>
  <table class="tabledata" cellpadding="0" cellspacing="0">
    <thead>
      <tr>
        <th v-for="col in tableColumns">{{ col }}</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="rowData in tableData" @click="rowClick(rowData.raw, $event)">
        <td v-for="obj, col in rowData.formatted" :width="obj.width">{{ obj.value }}</td>
      </tr>
    </tbody>
  </table>
</template>

<script>
// allTableData is an array of objects. Interface for that object:
//  {
//    formatted: {
//      'Column A Name': {
//        value: 'Data for column a',
//        width: '25%'
//      },
//      'Column B Name': { ... }
//    }
//    raw: ...raw row data
//  }
export default {
  props: ['allTableData'],
  computed: {
    tableData() {
      return this.allTableData || [];
    },
    tableColumns() {
      return this.allTableData[0] ? Object.keys(this.allTableData[0].formatted) : [];
    }
  },
  methods: {
    rowClick(rowData, event) {
      let classname = event.target.parentElement.className;
      this.$eventHub.$emit('deselectRows');
      event.target.parentElement.className = classname === '' ? 'isactive' : '';
      this.$eventHub.$emit('rowClickEvent', rowData, classname !== '');
    }
  },
  mounted() {
    this.$eventHub.$on('deselectRows', () => {
      document.querySelectorAll('tr.isactive').forEach(n => n.className = '');
    })
  }
}
</script>
