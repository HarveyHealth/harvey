<template>
    <div class="section">
        <h2 class="title">Pick a time</h2>
        <ul class="columns">
            <li v-for="time in times" class="column" @click="onTimeChange(time)">
                <a
                    :class="['box', 'has-text-centered', 'datetime-selector', {'is-selected' : selectedTime == time}]"
                    :disabled="time < startTime"
                >
                    <p>{{ setsTimeObject(time) | datetime('h a') }} - {{ setsTimeObject(time, duration) | datetime('h a') }}</p>
                </a>
            </li>
        </ul>
    </div>
</template>

<script>
    import moment from 'moment';

    export default {
        props: ['selectedDate', 'selectedTime', 'now', 'startOfDayHour', 'endOfDayHour', 'minimumNotice', 'duration', 'startDateTime'],
        methods: {
            range(start, stop, step) {
                if (stop == null) {
                    stop = start || 0
                    start = 0
                }
                step = step || 1

                var length = Math.max(Math.ceil((stop - start) / step), 0),
                    range = [],
                    index = 0

                for (index; index < length; index++, start += step) {
                    range[index] = start
                }

                return range
            },
            setsTimeObject(hour, hourOffset = 0) {
                return moment({hour: hour + hourOffset, minute: 0});
            },
            onTimeChange(time) {
                if (time >= this.startTime) {
                    this.$eventHub.$emit('datetime-change', {type: 'time', value: time});
                }
            }
        },
        computed: {
            times() {
                 return this.range(this.startOfDayHour, this.endOfDayHour, this.duration);
            },
            startTime() {
                if (this.selectedDate > this.startDateTime) {
                    return this.startOfDayHour;
                } else {
                    return this.startDateTime.hour();
                }
            }
        },
        mounted() {
            this.onTimeChange(this.startTime);
        }
    }
</script>

<style lang="sass" scoped>
    .datetime-selector {
        color: inherit;
        font-size: 0.9rem;
        font-weight: 400;
        &[disabled] {
            opacity: 0.5;
            cursor: default;
            box-shadow: none;
        }
    }
</style>