<template>
  <table class="tabledata" cellpadding="0" cellspacing="0">
    <thead>
      <tr>
        <th v-for="col in columns"
            @click="onSort ? onSort(col) : null"
            :width="col.width"
        >{{ col.name }}</th>
      </tr>
    </thead>
    <tbody>
      <tr v-show="loading">
        <td :colspan="columns.length" style="font-style: italic;">
          {{ loadingMsg }}
        </td>
      </tr>
      <tr v-show="!loading && !rowData.length">
        <td :colspan="columns.length" style="font-style: italic;">
          {{ emptyMsg }}
        </td>
      </tr>
      <tr v-for="(row, i) in rowData"
          @click="onRowClick(row, i)"
          :class="{ 'is-selected': selectedRow === row.data }">
        <td v-for="val in row.values">{{ val }}</td>
      </tr>
    </tbody>
  </table>
</template>

<script>
export default {
  props: {
    columns: {
      type: Array,
      required: true
    },
    emptyMsg: {
      type: String,
      default: 'No data found'
    },
    loading: {
      type: Boolean,
    },
    loadingMsg: {
      type: String,
      default: 'Loading...'
    },
    onRowClick: {
      type: Function
    },
    onSort: {
      type: Function,
    },
    rowData: {
      type: Array,
      required: true
    },
    selectedRow: {
      required: true
    }
  }
}
</script>
