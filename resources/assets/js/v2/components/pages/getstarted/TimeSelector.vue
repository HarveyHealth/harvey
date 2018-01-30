<template>
    <ScheduleList v-if="State.getstarted.signup.selectedDate">
        <li v-for="(time, i) in timeData">
            <BubbleSelection :isActive="selectedTime === i" :onClick="() => handleTimeSelection(time, i)">
                {{ time | timeDisplay }}
            </BubbleSelection>
        </li>
    </ScheduleList>
</template>

<script>
import { BubbleSelection } from 'inputs';
import ScheduleList from './ScheduleList.vue';

export default {
    props: {
        timeData: Array
    },
    components: {
        BubbleSelection,
        ScheduleList
    },
    computed: {
        selectedTime() {
            return this.State.getstarted.signup.selectedTime;
        }
    },
    methods: {
        handleTimeSelection(time, index) {
            App.State.getstarted.signup.stepsCompleted.schedule = false;
            App.State.getstarted.signup.appointmentIsSelected = false;
            App.State.getstarted.signup.selectedTime = index;
            App.State.getstarted.signup.data.appointment_at = this.Util.time.toUtc(time);
        }
    }
};
</script>
