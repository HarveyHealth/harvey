<template>
    <tr class="cell-wrap grabbable" :class="isSelected">
        <td class="sku-table__column sort"><i class="fa fa-bars"></i></td>
        <td class="sku-table__column">{{override.attributes.date}}</td>
        <td class="sku-table__column">{{displayStartBlock}}</td>
        <td class="sku-table__column">{{displayStopBlock}}</td>
        <td class="sku-table__column">{{displayShifts}}</td>
        <td class="sku-table__column">{{displayDuration}} hours</td>
        <td class="sku-table__column">{{displayNotes}}</td>
    </tr>
</template>

<script>
import moment from "moment";

export default {
  data() {
    return {};
  },
  components: {},
  methods: {},
  computed: {
    isSelected() {
      return this.selectedOverrideBlock.id === this.override.id ? "is-selected" : "";
    },
    startBlock() {
      return this.timeBlocks.filter(
        block => block.start_value === this.override.attributes.start_time
      )[0];
    },
    stopBlock() {
      return this.timeBlocks.filter(
        block => block.stop_value === this.override.attributes.stop_time
      )[0];
    },
    displayStartBlock() {
      return this.startBlock ? `${this.startBlock.title} (${this.startBlock.subtitle})` : '';
    },
    displayStopBlock() {
      return this.stopBlock ? `${this.stopBlock.title} (${this.stopBlock.subtitle})` : '';
    },
    displayDuration() {
      const startTime = moment(this.override.attributes.start_time, "HH:mm:ss");
      const stopTime = moment(this.override.attributes.stop_time, "HH:mm:ss");
      const duration = moment.duration(stopTime.diff(startTime));
      const hours = parseInt(duration.asHours());
      const minutes = parseInt(duration.asMinutes()) - hours * 60 > 0 ? 0.5 : 0;
      return hours + minutes;
    },
    displayShifts() {
      return this.displayDuration / 1.5;
    },
    displayNotes() {
      return this.override.attributes.notes || "N/A";
    }
  },
  mounted() {},
  props: {
    override: {
      type: Object
    },
    selectedOverrideBlock: {
      type: Object
    },
    timeBlocks: {
      type: Array
    }
  }
};
</script>
