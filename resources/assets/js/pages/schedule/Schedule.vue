<template>
    <div class="main-container">
        <UserNav />
        <div class="main-content">
            <NotificationPopup
                v-if="notificationError !== undefined && notificationActive !== undefined && notificationDirection !== undefined && notificationSymbol !== undefined && notificationMessage !== undefined"
                :as-error="notificationError"
                :active="notificationActive"
                :comes-from="notificationDirection"
                :symbol="notificationSymbol"
                :text="notificationMessage"
            />
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
                        <button @click="newScheduleModalOpen" class="button is-primary heading-buttons__button">Create New</button>
                    </div>
                </div>
            </div>
            <Flyout
                :active="activeModal"
                :heading="flyoutHeading"
                :on-close="modalClose">
                <ScheduleBlockForm
                    :submitting="submitting"
                    :timeBlocks="timeBlocks"
                    :scheduleBlock="selectedBlock"
                    :errorMessages="errorMessages"
                    @postSchedule="postSchedule"
                    @patchSchedule="patchSchedule"
                    @inputChanged="formChanged"/>
            </Flyout>
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
            NotificationPopup
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
                activeModal: false,
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
            newScheduleModalOpen() {
                this.clearSelectedBlock();
                this.modalOpen();
            },
            modalClose() {
                this.activeModal = false;
                setTimeout(() => this.clearSelectedBlock(), 300);
            },
            clearSelectedBlock() {
                this.selectedBlock = blankScheduleBlock;
            },
            modalOpen() {
                this.activeModal = false;
                setTimeout(() => this.activeModal = true, 300);
            },
            setSelectedBlock(block) {
                this.selectedBlock = block;
                this.activeModal = true;
            },
            postSchedule() {
                this.submitting = true;
                axios.post(`api/v1/practitioners/${this.practitionerId}/schedule`, this.selectedBlock.attributes)
                .then(response => {
                    this.scheduleBlocks.push(response.data.data);
                    this.selectedBlock = {};
                    this.activeModal = false;
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
                    this.selectedBlock = {};
                    this.activeModal = false;
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
</style>
