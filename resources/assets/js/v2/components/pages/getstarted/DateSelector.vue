<template>
    <div>
        <div v-for="(week, i) in weekData" v-show="hasAvailableDays(week.days)">
            <div class="week-heading cf">
                <span class="f7 fw3 fl">
                    {{ weekReferences[i] }}
                </span>
                <span class="f7 fw3 fr">
                    {{ week.start | weekDay }} - {{ week.end | weekDay }}
                </span>
            </div>
            <ScheduleList>
                <li v-for="(dayObj, key) in week.days" v-show="dayObj !== null">
                    <BubbleSelection
                        :isActive="selectedWeek === i && selectedDay === key"
                        :onClick="() => handleDateSelection(i, key, dayObj)"
                        v-text="key"
                    />
                </li>
            </ScheduleList>
        </div>
    </div>
</template>

<script>
import { BubbleSelection } from 'inputs';
import ScheduleList from './ScheduleList.vue';

export default {
    props: {
        onSelect: Function,
        weekData: Array
    },
    components: {
        BubbleSelection,
        ScheduleList
    },
    data() {
        return {
            weekReferences: [
                'This week',
                'Next week',
                'The week after',
                'In three weeks'
            ]
        }
    },
    computed: {
        selectedDay() {
            return this.State('getstarted.signup.selectedDay');
        },
        selectedWeek() {
            return this.State('getstarted.signup.selectedWeek');
        }
    },
    methods: {
        handleDateSelection(index, day, dayObj) {
          if (dayObj && dayObj.times.length) {
            // reset
            App.setState({
                'getstarted.signup.stepsCompleted.schedule': false,
                'getstarted.signup.selectedDate': null,
                'getstarted.signup.selectedTime': null,
                'getstarted.signup.data.appointment_at': null
            });

            // If sections are stacked at smaller screen width, scroll to time selection
            if (this.onSelect) {
                this.onSelect();
            }

            App.setState({
                'getstarted.signup.selectedWeek': index,
                'getstarted.signup.selectedDate': dayObj.date,
                'getstarted.signup.selectedDay': day,
                'getstarted.signup.selectedTimes': dayObj.times
            });
          }
        },
        hasAvailableDays(days) {
            let pass = false;
            for (let day in days) {
                if (days[day] !== null) pass = true;
            }
            return pass;
        },
    }
}
</script>

<style lang="scss">
    @import '~sass';

    .week-heading {
        border-bottom: 1px solid $color-gray-4;

        span {
            color: $color-copy-muted-1;
        }
    }
</style>
