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
                <p>This will remove your availability for the selected timeslot.</p>
                <div class="button-wrapper">
                    <button @click="deleteSchedule" class="button">Yes, Delete.</button>
                </div>
            </div>
        </Modal>
        <Flyout
                :active="activeFlyout"
                :heading="flyoutHeading"
                :on-close="closeFlyout">
                <ScheduleBlockForm
                    :submitting="submitting"
                    :timeBlocks="timeBlocks"
                    :scheduleBlock="selectedBlock"
                    :errorMessages="errorMessages"
                    @postSchedule="postSchedule"
                    @patchSchedule="patchSchedule"
                    @deleteSchedule="confirmDelete"
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

            <table class="sku-table tabledata appointments-table" v-if="loading">
                <td class="font-italic font-sm copy-muted">Loading schedule...</td>
            </table>
            <table class="sku-table tabledata appointments-table" v-if="!loading">
                <thead>
                    <th class="sku-table__column sku-table__move-icon heading-2 sort">Sort</th>
                    <th class="sku-table__column heading-2">Day of Week</th>
                    <th class="sku-table__column heading-2">First Block</th>
                    <th class="sku-table__column heading-2">Last Block</th>
                    <th class="sku-table__column heading-2">Shifts</th>
                    <th class="sku-table__column heading-2">Available Time</th>
                    <th class="sku-table__column heading-2">Notes</th>
                </thead>
                <tbody class="copy-main font-sm">
                    <ScheduleRow
                        v-for="schedule in scheduleBlocks"
                        :schedule="schedule"
                        :key=schedule.id
                        :selectedBlock="selectedBlock"
                        :timeBlocks="timeBlocks"
                        @click.native="setSelectedBlock(schedule)"
                    ></ScheduleRow>
                </tbody>
            </table>
        </div>

        <div class="main-content">
            <div class="main-header">
                <div class="container container-backoffice container-flex">
                    <h1 class="heading-1">
                        <span class="text">Overrides</span>
                    </h1>
                </div>
            </div>

            <table class="sku-table tabledata appointments-table" v-if="loading">
                <td class="font-italic font-sm copy-muted">Loading schedule...</td>
            </table>
            <table class="sku-table tabledata appointments-table" v-if="!loading">
                <thead>
                    <th class="sku-table__column sku-table__move-icon heading-2 sort">Sort</th>
                    <th class="sku-table__column heading-2">Day of Week</th>
                    <th class="sku-table__column heading-2">First Block</th>
                    <th class="sku-table__column heading-2">Last Block</th>
                    <th class="sku-table__column heading-2">Shifts</th>
                    <th class="sku-table__column heading-2">Available Time</th>
                    <th class="sku-table__column heading-2">Notes</th>
                </thead>
                <tbody class="copy-main font-sm">
                    <ScheduleRow
                        v-for="schedule in scheduleBlocks"
                        :schedule="schedule"
                        :key=schedule.id
                        :selectedBlock="selectedBlock"
                        :timeBlocks="timeBlocks"
                        @click.native="setSelectedBlock(schedule)"
                    ></ScheduleRow>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import NotificationPopup from '../../commons/NotificationPopup.vue';
    import UserNav from '../../commons/UserNav.vue';
    import Flyout from '../../commons/Flyout.vue';
    import ScheduleRow from './components/ScheduleRow.vue';
    import ScheduleBlockForm from './components/ScheduleBlockForm.vue';
    import _ from 'lodash';
    import { Modal } from 'layout';

    const blankScheduleBlock = {
        id: '',
        attributes: {
            day_of_week: '',
            start_time: '',
            stop_time: '',
            notes: ''
        }
    };

    export default {
        name: 'schedule',
        components: {
            UserNav,
            Flyout,
            ScheduleRow,
            ScheduleBlockForm,
            NotificationPopup,
            Modal
        },
        data() {
            return {
                errorSymbol: '!',
                errorMessage: 'Error retrieving data',
                successSymbol: '&#10003;',
                successMessage: 'Changes Saved',
                notificationError: false,
                notificationSymbol: '&#10003;',
                notificationMessage: 'Changes Saved',
                notificationActive: false,
                notificationDirection: 'top-right',
                errorMessages: null,
                loading: true,
                submitting: false,
                activeFlyout: false,
                modalActive: false,
                practitionerId: '',
                scheduleBlocks: [blankScheduleBlock],
                selectedBlock: blankScheduleBlock,
                selectedType: 'schedule',
                selectOptions: [
                    {
                        title: 'Schedule',
                        value: 'schedule'
                    },
                    {
                        title: 'Override',
                        value: 'override'
                    }
                ],
                timeBlocks: [
                    {
                        title: 'Morning 1',
                        subtitle: '9a-10:30a',
                        start_value: '09:00:00',
                        stop_value: '10:30:00'
                    },
                    {
                        title: 'Morning 2',
                        subtitle: '10:30a-12p',
                        start_value: '10:30:00',
                        stop_value: '12:00:00'
                    },
                    {
                        title: 'Afternoon 1',
                        subtitle: '12p-1:30p',
                        start_value: '12:00:00',
                        stop_value: '13:30:00'
                    },
                    {
                        title: 'Afternoon 2',
                        subtitle: '1:30p-3p',
                        start_value: '13:30:00',
                        stop_value: '15:00:00'
                    },
                    {
                        title: 'Afternoon 3',
                        subtitle: '3p-4:30p',
                        start_value: '15:00:00',
                        stop_value: '16:30:00'
                    },
                    {
                        title: 'Afternoon 4',
                        subtitle: '4:30p-6p',
                        start_value: '16:30:00',
                        stop_value: '18:00:00'
                    },
                    {
                        title: 'Evening 1',
                        subtitle: '6p-7:30p',
                        start_value: '18:00:00',
                        stop_value: '19:30:00'
                    },
                    {
                        title: 'Evening 2',
                        subtitle: '7:30p-9:00p',
                        start_value: '19:30:00',
                        stop_value: '21:00:00'
                    }
                ]
            };
        },
        methods: {
            flashNotification() {
                this.notificationActive = true;
                setTimeout(() => this.notificationActive = false, 3000);
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
            },
            openFlyout() {
                this.activeFlyout = false;
                setTimeout(() => this.activeFlyout = true, 300);
            },
            setSelectedBlock(block) {
                this.selectedBlock = block;
                this.activeFlyout = true;
            },
            postSchedule() {
                this.submitting = true;
                axios.post(`api/v1/practitioners/${this.practitionerId}/schedule`, this.selectedBlock.attributes)
                .then(response => {
                    this.scheduleBlocks.push(response.data.data);
                    this.clearSelectedBlock();
                    this.activeFlyout = false;
                    this.submitting = false;
                    this.flashNotification();
                })
                .catch((e) => {
                    this.errorMessages = e.response.data.errors;
                    this.submitting = false;
                });
            },
            patchSchedule() {
                this.submitting = true;
                axios.patch(`api/v1/practitioners/${this.practitionerId}/schedule/${this.selectedBlock.id}`, this.selectedBlock.attributes)
                .then(() => {
                    this.clearSelectedBlock();
                    this.activeFlyout = false;
                    this.submitting = false;
                    this.flashNotification();
                })
                .catch((e) => {
                    this.errorMessages = e.response.data.errors;
                    this.submitting = false;
                });
            },
            deleteSchedule() {
                this.submitting = true;
                axios.delete(`api/v1/practitioners/${this.practitionerId}/schedule/${this.selectedBlock.id}`)
                .then(() => {
                   _.remove(this.scheduleBlocks, this.selectedBlock);
                    this.clearSelectedBlock();
                    this.activeFlyout = false;
                    this.modalActive = false;
                    this.submitting = false;
                    this.flashNotification();
                })
                .catch((e) => {
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
            }
        },
        computed: {
            flyoutHeading() {
                return _.isEmpty(this.selectedBlock.id) ? 'New Schedule Block' : 'Update Schedule';
            }
        },
        created() {
            this.$root.$data.global.currentPage = 'schedule';
        },
        mounted() {
            this.practitionerId = Laravel.user.practitionerId;
            axios.get(`api/v1/practitioners/${this.practitionerId}/schedule`)
            .then(response => {
                this.scheduleBlocks = response.data.data;
                this.loading = false;
            });
        }
    };
</script>

<style lang="scss">
    .heading-buttons {
        display: flex;
        flex: 1;
        justify-content: end;
        align-items: baseline;

        &__button {
            margin-left: 10px;
            padding: 11px 30px;
         }
    }

    .main-header {
        padding: 0;
    }
</style>
