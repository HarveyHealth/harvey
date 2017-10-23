<template>
    <div class="section">
        <h2 class="title is-4">Pick a date</h2>
        <ul class="columns">
            <li v-for="date in dates" class="column" @click="onDateChange(date)">
                <a :class="['box', 'has-text-centered', 'datetime-selector', {'is-selected' : isSameDate(selectedDate, date)}]">
                    <p class="subtitle">{{date | datetime('dddd') }}</p>
                    <p class="subtitle"><small>{{date | datetime('MMM') }}</small> </p>
                    <p class="title">{{date | datetime('DD') }}</p>
                </a>
            </li>
        </ul>
    </div>
</template>

<script>
    import moment from 'moment';

    export default {
        props: ['selectedDate', 'maximumDays', 'startDateTime'],
        methods: {
            onDateChange(date) {
                this.$eventHub.$emit('datetime-change', {type: 'date', value: moment(date).utc()});
            },
            isSameDate(a, b) {
                return a == b || (moment(a).isValid() && moment(a).diff(b) == 0);
            }
        },
        computed: {
            dates() {
                let dates = new Array();
                let currentDate = moment(this.startDateTime).local();

                for (var i = 1; i <= this.maximumDays; i++) {
                    dates.push( currentDate );
                    currentDate = moment(this.startDateTime).local().add(i, 'days');
                }

                return dates;
            }
        },
        mounted() {
            this.onDateChange(this.dates[0]);
        }
    };
</script>