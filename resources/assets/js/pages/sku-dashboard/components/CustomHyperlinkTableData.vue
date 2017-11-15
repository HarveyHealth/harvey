<template>
  <table :class="$$tableClasses" cellpadding="0" cellspacing="0">
    <thead>
      <tr>
        <th v-for="col in columns"
            @click="onSort ? onSort(col) : null"
            :width="col.width"
            class="heading-2"
        >{{ col.name }}</th>
      </tr>
    </thead>
    <tbody class="copy-main font-sm">
      <tr v-show="loading">
        <td :colspan="columns.length" class="copy-muted font-sm font-italic">
          {{ loadingMsg }}
        </td>
      </tr>
      <tr v-show="!loading && !rowData.length">
        <td :colspan="columns.length" class="copy-muted font-sm font-italic">
          {{ emptyMsg }}
        </td>
      </tr>
      <tr v-for="(row, i) in rowData"
          @click="onRowClick(row, i)"
          :class="$$rowClasses(row.data, i)">
        <td v-for="(val, j) in row.values" :width="columns[j].width">
          <i class="fa fa-refresh fa-spin" v-if="j === 0 && updatingRow === i"></i>
          <div v-if="j !== row.data.email_hyperlink && j !== row.data.phone_hyperlink" class="cell-wrap" :data-column="columns[j].name">{{ val.replace(/<\/?[^>]+(>|$)/g, "") }}</div>
           <a :href="'mailto:'+row.data.email" v-if="j === row.data.email_hyperlink" class="cell-wrap" :data-column="columns[j].name">{{ val.replace(/<\/?[^>]+(>|$)/g, "") }}</a>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script>
import { phone } from '../../../utils/filters/textformat';

export default {
  name: 'TableData',
  data() {return {};},
  computed: {
    // Includes any classes passed with tableClass
    $$tableClasses() {
      return {
        tabledata: true,
        [`${this.tableClass}`]: true
      };
    }
  },
  filters: {
    phone
  },
  methods: {
    // This is a method so it can freshly render and respond to the index
    // of a particular item in the row data. The classes indicate if a row
    // has been selected, is currently updating, or has just finished updating.
    $$rowClasses(data, index) {
      return {
        'is-selected': this.selectedRow === data,
        'is-updating': this.updatingRow === index,
        'has-updated': this.updatedRow === index
      };
    }
  },
  props: {
    // Defines the column based on the following data:
    // [
    //   {
    //     key: 'date',
    //     name: 'Date',
    //     width: '15%',
    //     sort: (prop) => {
    //       return (a, b) => {
    //         return a.data[prop] < b.data[prop];
    //       }
    //     }
    //   }
    // ]
    // The sort option should be a function that takes a property name from the
    // passed row data and returns a function that can be passed to the native
    // Array.sort method. See rowData and it will make more sense.
    columns: {
      type: Array,
      required: true
    },
    // The message to display when data loading is done and the set returned is empty
    emptyMsg: {
      type: String,
      default: 'You do not have any clients.'
    },
    // Whether the table data is still loading or not
    loading: {
      type: Boolean
    },
    // The message to display if the component mounts and loading is still true
    loadingMsg: {
      type: String,
      default: 'Loading your clients...'
    },
    // What happens when a row is clicked.
    // Function takes the row data and the row data index as arguments.
    onRowClick: {
      type: Function
    },
    // What happens when a table column header is clicked.
    // Function takes the column data as an argument
    onSort: {
      type: Function
    },
    // The set of row data rendered based on the following structure:
    // [
    //   {
    //     data: { _date: '5/6/17 18:30:00', _id: 3, date: 'Monday', name: 'Joe', email: 'joe@shmoe.com' },
    //     values: ['Monday', 'Joe', 'joe@shmoe.com']
    //   }
    // ]
    // The 'data' property contains the full set of data associated with a given row.
    // The 'values' property is an array of the table cell values to display in the order of columns given.
    // Given the above column data, the sort function would probably take '_date' as the property to be used.
    rowData: {
      type: Array,
      required: true
    },
    // Can be null to start but should be used to store the index of the row clicked
    selectedRow: {
        type: Object,
        required: false
    },
    // To add custom class to the table for additional styling
    tableClass: {
      type: String
    },
    // Like selectedRow but to indicate the index of the row that was just updated
    updatedRow: {
      required: false,
      type: Number
    },
    // Like selectedRow but to indicate the index of the row currently being updated
    updatingRow: {
      required: false,
      type: Number
    }
  }
};
</script>
