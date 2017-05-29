<template>
  <div class="main-container">

    <UserNav />

    <div class="main-content">

      <div class="main-header">
        <div class="container">
          <h1 class="title header-xlarge">
            <span class="text">Your Appointments</span>
            <button class="button main-action circle">
              <svg><use xlink:href="#addition"/></svg>
            </button>
          </h1>
        </div>
      </div>

      <TableData :columns="tableColumns" :tabledata="_appointments" />

    </div>

  </div>
</template>

<script>
// components
import TableData from '../../commons/TableData.vue';
import UserNav from '../../commons/UserNav.vue';

// other
import tableColumns from './tableColumns';
import tableDataTransform from './tableDataTransform';

export default {
  data() {
    return {
      selectedRow: null,
      tableColumns
    }
  },
  name: 'appts',
  components: {
    TableData,
    UserNav
  },
  computed: {
    _appointments() {
      return tableDataTransform(this.$root.$data.global.appointments);
    }
  },
  methods: {

  },
  mounted() {
    this.$eventHub.$on('eventRowClick', (obj, index) => {
      console.log(obj);
    })
  },
  destroyed() {
    this.$eventHub.$off('eventRowClick');
  }
}
</script>

<style lang="css">
</style>
