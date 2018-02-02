<template>
    <div class="main-container">
        <UserNav />
        <NotificationPopup
            v-if="notificationError !== undefined && notificationActive !== undefined && notificationDirection !== undefined && notificationSymbol !== undefined && notificationMessage !== undefined"
            :as-error="notificationError"
            :active="notificationActive"
            :comes-from="notificationDirection"
            :symbol="notificationSymbol"
            :text="notificationMessage"
        />
        <Modal
            :active="modalActive"
            :hideClose="false"
            :isSimple="true"
            :onClose="closeModal">
            <div class="inline-centered" slot="content">
                <h1 class="header-xlarge"><span class="text">Are you sure?</span></h1>
                <p v-if="selectedType=='schedule'">This will remove your availability for the selected timeslot.</p>
                <p v-if="selectedType=='override'">This will remove your schedule override.</p>
                <div class="button-wrapper">
                    <button @click="deleteItem" class="button">Yes, Delete.</button>
                </div>
            </div>
        </Modal>
        <Flyout
                :active="activeFlyout"
                :heading="flyoutHeading"
                :on-close="closeFlyout">
                <ScheduleBlockForm
                    v-if="selectedType == 'schedule'"
                    :submitting="submitting"
                    :timeBlocks="timeBlocks"
                    :scheduleBlock="selectedBlock"
                    :errorMessages="errorMessages"
                    @postSchedule="postSchedule"
                    @patchSchedule="patchSchedule"
                    @deleteSchedule="confirmDelete"
                    @inputChanged="formChanged"/>
                <ScheduleBlockOverrideForm
                    v-if="selectedType == 'override'"
                    :submitting="submitting"
                    :timeBlocks="timeBlocks"
                    :scheduleOverrideBlock="selectedOverrideBlock"
                    :errorMessages="errorMessages"
                    @postOverride="postOverride"
                    @patchOverride="patchOverride"
                    @deleteOverride="confirmDelete"
                    @inputChanged="formChanged"/>
            </Flyout>
        <div class="main-content">
            <div class="main-header">
                <div class="container container-backoffice container-flex">
                    <h1 class="heading-1">
                        <span class="text">Schedule</span>
                    </h1>
                    <div class="heading-buttons">
                        <span class="custom-select">
                            <select v-model="selectedType">
                                <option v-for="(option, index) in selectOptions" :value="option.value" :key="index">{{option.title}}</option>
                            </select>
                        </span>
                        <button @click="newScheduleOpenFlyout" class="button is-primary heading-buttons__button">Create New</button>
                    </div>
                </div>
            </div>

            <ScheduleTable
                :selected-row="selectedBlock"
                :handle-row-click="handleRowClick"
                :loading="loading"
                :tableRowData="scheduleData"
             />
        </div>

        <div class="main-content">
            <div class="main-header">
                <div class="container container-backoffice container-flex">
                    <h1 class="heading-1">
                        <span class="text">Overrides</span>
                    </h1>
                </div>
            </div>

            <OverrideTable
                :selected-row="selectedOverrideBlock"
                :handle-row-click="handleOverrideRowClick"
                :loading="loading"
                :tableRowData="overrideData"
             />

            <!-- <table class="sku-table tabledata appointments-table" v-if="loading">
                <td class="font-italic font-sm copy-muted">Loading schedule...</td>
            </table>
            <table class="sku-table tabledata appointments-table" v-if="!loading">
                <thead>
                    <th class="sku-table__column sku-table__move-icon heading-2 sort">Sort</th>
                    <th class="sku-table__column heading-2">Date</th>
                    <th class="sku-table__column heading-2">First Block</th>
                    <th class="sku-table__column heading-2">Last Block</th>
                    <th class="sku-table__column heading-2">Shifts</th>
                    <th class="sku-table__column heading-2">Blocked Time</th>
                    <th class="sku-table__column heading-2">Notes</th>
                </thead>
                <tbody class="copy-main font-sm">
                    <ScheduleOverrideRow
                        v-for="override in scheduleOverrideBlocks"
                        :override="override"
                        :key=override.id
                        :selectedOverrideBlock="selectedOverrideBlock"
                        :timeBlocks="timeBlocks"
                        @click.native="setSelectedOverrideBlock(override)"
                    ></ScheduleOverrideRow>
                </tbody>
            </table> -->
        </div>
    </div>
</template>

<script>
import axios from "axios";
import NotificationPopup from "../../commons/NotificationPopup.vue";
import UserNav from "../../commons/UserNav.vue";
import Flyout from "../../commons/Flyout.vue";
import ScheduleRow from "./components/ScheduleRow.vue";
import ScheduleOverrideRow from "./components/ScheduleOverrideRow.vue";
import ScheduleBlockForm from "./components/ScheduleBlockForm.vue";
import ScheduleBlockOverrideForm from "./components/ScheduleBlockOverrideForm.vue";
import _ from "lodash";
import { Modal } from "layout";
import ScheduleTable from './components/ScheduleTable.vue';
import OverrideTable from './components/OverrideTable.vue';
import moment from 'moment';

const blankScheduleBlock = {
  id: "",
  attributes: {
    day_of_week: "",
    start_time: "",
    stop_time: "",
    notes: ""
  }
};

const blankScheduleOverrideBlock = {
  id: "",
  attributes: {
    date: "",
    start_time: "",
    stop_time: "",
    notes: ""
  }
};

export default {
  name: "schedule",
  components: {
    UserNav,
    Flyout,
    ScheduleRow,
    ScheduleOverrideRow,
    ScheduleBlockForm,
    ScheduleBlockOverrideForm,
    NotificationPopup,
    Modal,
    ScheduleTable,
    OverrideTable
  },
  data() {
    return {
      errorSymbol: "!",
      errorMessage: "Error retrieving data",
      successSymbol: "&#10003;",
      successMessage: "Changes Saved",
      notificationError: false,
      notificationSymbol: "&#10003;",
      notificationMessage: "Changes Saved",
      notificationActive: false,
      notificationDirection: "top-right",
      errorMessages: null,
      loading: true,
      submitting: false,
      activeFlyout: false,
      modalActive: false,
      practitionerId: "",
      scheduleBlocks: [blankScheduleBlock],
      selectedBlock: blankScheduleBlock,
      scheduleOverrideBlocks: [blankScheduleOverrideBlock],
      selectedOverrideBlock: blankScheduleOverrideBlock,
      selectedType: "schedule",
      selectOptions: [
        {
          title: "Schedule",
          value: "schedule"
        },
        {
          title: "Override",
          value: "override"
        }
      ],
      timeBlocks: [
        {
          title: "Morning 1",
          subtitle: "9a-10:30a",
          start_value: "09:00:00",
          stop_value: "10:30:00"
        },
        {
          title: "Morning 2",
          subtitle: "10:30a-12p",
          start_value: "10:30:00",
          stop_value: "12:00:00"
        },
        {
          title: "Afternoon 1",
          subtitle: "12p-1:30p",
          start_value: "12:00:00",
          stop_value: "13:30:00"
        },
        {
          title: "Afternoon 2",
          subtitle: "1:30p-3p",
          start_value: "13:30:00",
          stop_value: "15:00:00"
        },
        {
          title: "Afternoon 3",
          subtitle: "3p-4:30p",
          start_value: "15:00:00",
          stop_value: "16:30:00"
        },
        {
          title: "Afternoon 4",
          subtitle: "4:30p-6p",
          start_value: "16:30:00",
          stop_value: "18:00:00"
        },
        {
          title: "Evening 1",
          subtitle: "6p-7:30p",
          start_value: "18:00:00",
          stop_value: "19:30:00"
        },
        {
          title: "Evening 2",
          subtitle: "7:30p-9:00p",
          start_value: "19:30:00",
          stop_value: "21:00:00"
        }
      ]
    };
  },
  methods: {
    handleRowClick(row, index) {
        this.clearSelectedBlock();
        this.setSelectedBlock(this.scheduleBlocks[index]);
    },
    handleOverrideRowClick(row, index) {
        this.clearSelectedBlock();
        this.setSelectedOverrideBlock(this.scheduleOverrideBlocks[index]);
    },
    flashNotification() {
      this.notificationActive = true;
      setTimeout(() => (this.notificationActive = false), 3000);
    },
    callErrorNotification(msg) {
      this.notificationError = true;
      this.notificationSymbol = this.errorSymbol;
      this.notificationMessage = msg || this.errorMessage;
      this.flashNotification();
    },
    newScheduleOpenFlyout() {
      this.clearSelectedBlock();
      this.openFlyout();
    },
    closeFlyout() {
      this.activeFlyout = false;
      setTimeout(() => this.clearSelectedBlock(), 300);
    },
    clearSelectedBlock() {
      this.selectedBlock = blankScheduleBlock;
      this.selectedOverrideBlock = blankScheduleOverrideBlock;
    },
    openFlyout() {
      this.activeFlyout = false;
      setTimeout(() => (this.activeFlyout = true), 300);
    },
    setSelectedBlock(block) {
      this.selectedBlock = block;
      this.selectedType = "schedule";
      this.activeFlyout = true;
    },
    setSelectedOverrideBlock(override) {
      this.selectedOverrideBlock = override;
      this.selectedType = "override";
      this.activeFlyout = true;
    },
    postSchedule() {
      this.submitting = true;
      axios
        .post(
          `api/v1/practitioners/${this.practitionerId}/schedule`,
          this.selectedBlock.attributes
        )
        .then(response => {
          this.scheduleBlocks.push(response.data.data);
          this.clearSelectedBlock();
          this.activeFlyout = false;
          this.submitting = false;
          this.flashNotification();
        })
        .catch(e => {
          this.errorMessages = e.response.data.errors;
          this.submitting = false;
        });
    },
    patchSchedule() {
      this.submitting = true;
      axios
        .patch(
          `api/v1/practitioners/${this.practitionerId}/schedule/${this
            .selectedBlock.id}`,
          this.selectedBlock.attributes
        )
        .then(() => {
          this.clearSelectedBlock();
          this.activeFlyout = false;
          this.submitting = false;
          this.flashNotification();
        })
        .catch(e => {
          this.errorMessages = e.response.data.errors;
          this.submitting = false;
        });
    },
    deleteSchedule() {
      this.submitting = true;
      axios
        .delete(
          `api/v1/practitioners/${this.practitionerId}/schedule/${this
            .selectedBlock.id}`
        )
        .then(() => {
          const blockIndex = this.scheduleBlocks.indexOf(this.selectedBlock);
          this.scheduleBlocks = [
              ...this.scheduleBlocks.slice(0, blockIndex), ...this.scheduleBlocks.slice(blockIndex + 1)
          ];
          this.clearSelectedBlock();
          this.activeFlyout = false;
          this.modalActive = false;
          this.submitting = false;
          this.flashNotification();
        })
        .catch(e => {
          this.errorMessages = e.response.data.errors;
          this.submitting = false;
        });
    },
    formChanged() {
      this.errorMessages = null;
    },
    closeModal() {
      this.modalActive = false;
    },
    confirmDelete() {
      this.modalActive = true;
    },
    deleteItem() {
      if (this.selectedType === "schedule") {
        this.deleteSchedule();
      } else if (this.selectedType === "override") {
        this.deleteOverride();
      } else {
        this.modalActive = false;
        return;
      }
    },
    postOverride() {
      this.submitting = true;
      axios
        .post(
          `api/v1/practitioners/${this.practitionerId}/schedule-overrides`,
          this.selectedOverrideBlock.attributes
        )
        .then(response => {
          this.scheduleOverrideBlocks.push(response.data.data);
          this.clearSelectedBlock();
          this.activeFlyout = false;
          this.submitting = false;
          this.flashNotification();
        })
        .catch(e => {
          this.errorMessages = e.response.data.errors;
          this.submitting = false;
        });
    },
    patchOverride() {
      this.submitting = true;
      axios
        .patch(
          `api/v1/practitioners/${this.practitionerId}/schedule-overrides/${this
            .selectedOverrideBlock.id}`,
          this.selectedOverrideBlock.attributes
        )
        .then(() => {
          this.clearSelectedBlock();
          this.activeFlyout = false;
          this.submitting = false;
          this.flashNotification();
        })
        .catch(e => {
          this.errorMessages = e.response.data.errors;
          this.submitting = false;
        });
    },
    deleteOverride() {
      this.submitting = true;
      axios
        .delete(
          `api/v1/practitioners/${this.practitionerId}/schedule-overrides/${this
            .selectedOverrideBlock.id}`
        )
        .then(() => {
          const blockIndex = this.scheduleOverrideBlocks.indexOf(this.selectedOverrideBlock);
          this.scheduleOverrideBlocks = [
              ...this.scheduleOverrideBlocks.slice(0, blockIndex), ...this.scheduleOverrideBlocks.slice(blockIndex + 1)
          ];
          this.clearSelectedBlock();
          this.activeFlyout = false;
          this.modalActive = false;
          this.submitting = false;
          this.flashNotification();
        })
        .catch(e => {
          this.errorMessages = e.response.data.errors;
          this.submitting = false;
        });
    },
    startBlock(schedule) {
      return this.timeBlocks.filter(
        block => block.start_value === schedule.attributes.start_time
      )[0];
    },
    displayStartBlock(schedule) {
      return this.startBlock ? `${this.startBlock(schedule).title} (${this.startBlock(schedule).subtitle})` : '';
    },
    stopBlock(schedule) {
      return this.timeBlocks.filter(
        block => block.stop_value === schedule.attributes.stop_time
      )[0];
    },
    displayStopBlock(schedule) {
      return this.stopBlock ?`${this.stopBlock(schedule).title} (${this.stopBlock(schedule).subtitle})` : '';
    },
    displayDuration(schedule) {
      const startTime = moment(schedule.attributes.start_time, "HH:mm:ss");
      const stopTime = moment(schedule.attributes.stop_time, "HH:mm:ss");
      const duration = moment.duration(stopTime.diff(startTime));
      const hours = parseInt(duration.asHours());
      const minutes = parseInt(duration.asMinutes()) - hours * 60 > 0 ? 0.5 : 0;
      return hours + minutes;
    },
    displayShifts(schedule) {
      return this.displayDuration(schedule) / 1.5;
    },
    displayNotes(schedule) {
      return schedule.attributes.notes || "N/A";
    }
  },
  computed: {
    flyoutHeading() {
        if(this.selectedType === 'override') {
            return _.isEmpty(this.selectedOverrideBlock.id) ? 'New Override Block' : 'Update Override Block';
        } else {
            return _.isEmpty(this.selectedBlock.id) ? 'New Schedule Block' : 'Update Schedule Block';
        }
    },
    scheduleData() {
        return this.scheduleBlocks.map(scheduleBlock => {
            if(!scheduleBlock.id) {
                return [];
            }
            return {
                data: scheduleBlock,
                values: [
                    scheduleBlock.attributes.day_of_week,
                    this.displayStartBlock(scheduleBlock),
                    this.displayStopBlock(scheduleBlock),
                    this.displayShifts(scheduleBlock),
                    this.displayDuration(scheduleBlock),
                    this.displayNotes(scheduleBlock)
                ]
            };
        });
    },
    overrideData() {
        return this.scheduleOverrideBlocks.map(overrideBlock => {
            if(!overrideBlock.id) {
                return [];
            }
            return {
                data: overrideBlock,
                values: [
                    overrideBlock.attributes.date,
                    this.displayStartBlock(overrideBlock),
                    this.displayStopBlock(overrideBlock),
                    this.displayShifts(overrideBlock),
                    this.displayDuration(overrideBlock),
                    this.displayNotes(overrideBlock)
                ]
            };
        });
    }
  },
  created() {
    this.$root.$data.global.currentPage = "schedule";
  },
  mounted() {
    this.practitionerId = Laravel.user.practitionerId;

    axios
      .get(`api/v1/practitioners/${this.practitionerId}/schedule`)
      .then(response => {
        this.scheduleBlocks = response.data.data;
        this.loading = false;
      });

    axios
      .get(`api/v1/practitioners/${this.practitionerId}/schedule-overrides`)
      .then(response => {
        this.scheduleOverrideBlocks = response.data.data;
        this.loading = false;
      });
  }
};
</script>

<style lang="scss">
.tabledata tbody tr td {
    border: none;
}

.heading-buttons {
  align-items: baseline;
  display: flex;
  flex: 1;
  flex-direction: row;
  justify-content: flex-end;

  &__button {
    margin-left: 10px;
    padding: 11px 30px;
  }
}

.main-header {
  padding: 0;
}
</style>
