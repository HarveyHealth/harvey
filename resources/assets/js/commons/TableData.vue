<template>
  <table :class="$$tableClasses" cellpadding="0" cellspacing="0">
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
          :class="$$rowClasses(row.data, i)">
        <td v-for="(val, j) in row.values" :width="columns[j].width">
          <i class="fa fa-refresh fa-spin" v-if="j === 0 && updatingRow === i"></i>
          <div class="cell-wrap" :data-column="columns[j].name">{{ val }}</div>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script>
export default {
  computed: {
    $$tableClasses() {
      return {
        tabledata: true,
        [`${this.tableClass}`]: true,
      }
    }
  },
  methods: {
    $$rowClasses(data, index) {
      return {
        'is-selected': this.selectedRow === data,
        'is-updating': this.updatingRow === index,
        'has-updated': this.updatedRow === index,
      }
    }
  },
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
    },
    tableClass: {
      type: String
    },
    updatedRow: {
      required: false
    },
    updatingRow: {
      required: false
    }
  }
}
</script>
