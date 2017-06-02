<template>
  <table class="tabledata" cellpadding="0" cellspacing="0">
    <thead>
      <tr>
        <th
          v-for="obj in columns"
          @click="utilSort(obj)"
          :width="obj.width"
          :class="{
            'sortable': obj.sort,
            'sorted': sortedColumn === obj.key,
            'sort-up': sortAscending,
            'sort-down': !sortAscending
          }"
        >{{ obj.name }}</th>
      </tr>
    </thead>
    <tbody>
      <tr v-if="!dataReceived">
        <td :colspan="columns.length" style="font-style: italic;">{{ loadingmsg }}</td>
      </tr>
      <tr v-if="emptymsg && emptymsg !== ''">
        <td :colspan="columns.length" style="font-style: italic;">{{ emptymsg }}</td>
      </tr>
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
  props: ['columns', 'defaultsortcolumn', 'defaultsortmode', 'emptymsg', 'loadingmsg', 'tabledata'],

  data() {
    return {
      dataReceived: false,
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
        this.$eventHub.$emit('tableRowClick', obj, index);
      } else {
        this.selectedIndex = null;
        this.selectedObject = null;
        this.$eventHub.$emit('tableRowClick', null, null);
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
          this.tabledata = this.tabledata.sort(obj.sort);
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

    this.$eventHub.$on('tableDataReceived', data => {
      this.dataReceived = true;
      this.sortedColumn = null;
      if (data.length) {
        this.columns.forEach(obj => {
          if (this.defaultsortcolumn === obj.key) {
            this.utilSort(obj);
            if (this.defaultsortmode && this.defaultsortmode === 'descending') {
              this.utilSort(obj);
            }
          }
        });
      }
    });

    this.$eventHub.$on('tableRowUnselect', () => {
      this.selectedIndex = null;
      this.selectedObject = null;
    })

  },
  destroyed() {
    this.$eventHub.$off('tableDataReceived');
    this.$eventHub.$off('tableRowUnselect');
  }
}
</script>
