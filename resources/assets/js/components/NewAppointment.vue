<template>
    <div class="container">
        <div class="hero has-text-centered">
            <div class="hero-body">
                <h1 class="title">Choose appointment time</h1>
            </div>
        </div>
        <form
            role="form"
            method="POST"
            action="/api/appointments"
            @submit.prevent="onSubmit"
            @keydown="form.errors.clear($event.target.name)"
        >
            <date-picker
                :selected-date="form.selectedDate"
                :maximum-days="maximumDays"
                :start-date-time="startDateTime"
            >
            </date-picker>
            <time-picker
                :selected-date="form.selectedDate"
                :selected-time="form.selectedTime"
                :now="now"
                :start-of-day-hour="startOfDayHour"
                :end-of-day-hour="endOfDayHour"
                :minimum-notice="minimumNotice"
                :duration="duration"
                :start-date-time="startDateTime"
            >
            </time-picker>

            <div class="section control is-horizontal">
                <div class="control-label">
                    <h2 class="title">Details</h2>
                </div>
                <div class="control">
                    <textarea class="textarea" placeholder="Explain how we can help you" v-model="form.details"></textarea>
                </div>
            </div>
            
            <p class="control is-clearfix hero-buttons">
                <button
                    type="submit"
                    class="button is-medium is-primary"
                    :disabled="form.errors.any()"
                >Schedule</button>
            </p>
        </form>
    </div>
</template>

<script>
    import moment from 'moment';
    import Form from '../helpers.js';
    import DatePicker from './DatePicker.vue';
    import TimePicker from './TimePicker.vue';

    export default {
        name: 'new-appointment',
        props: ['user'],
        data() {
            return {
                form: new Form({
                    selectedDate: '',
                    selectedTime: '',
                    details: '',
                }),
                now: moment(),
                startOfDayHour: 9,
                endOfDayHour: 18,
                maximumDays: 7,
                minimumNotice: 2, // hours
                duration: 1 // hours
            }
        },
        components: {
            DatePicker,
            TimePicker
        },
        methods: {
            canBookToday() {
                let acceptableTime = moment(this.now).add(this.minimumNotice, 'hours');
                let endOfDayTime = moment(this.now).set({hour: this.endOfDayHour, minute: 0}).subtract(this.duration, 'hours');

                return acceptableTime <= endOfDayTime;
            },
            getNearestTime(time, interval) {
                var minutes = Math.ceil(Math.max(1, time.minutes()) / interval) * interval,
                    hours = time.hours()

                if (minutes == 60) {
                    hours++
                    minutes = 0
                    if (hours >= 24) {
                        hours = hours - 24
                    }
                }
                return hours
            },
            updateSelectedTime() {
                if ( this.form.selectedTime < this.startDateTime.hour() ) {
                    this.form.selectedTime = this.startDateTime.hour();
                }
            },
            onDateTimeChange(obj) {
                let field = 'selected' + obj.type.charAt(0).toUpperCase() + obj.type.slice(1);

                if (this.form[field] != obj.value) {
                    this.form[field] = obj.value;
                }

                // if date is changed to start day date
                if ( obj.type == 'date' && obj.value.day() == this.startDateTime.day() ) {
                    // need to make sure selected time doesn't go over the minimum time
                    this.updateSelectedTime();
                }
            },
            onSubmit() {
                this.form.submit('post', 'api/appointments', this.onSuccess);
            },
            onSuccess() {
                // if no detailed profile, redirect to profile
                if (!this.user.gender) {
                    this.$router.push('/profile');
                } else {
                // else redirect to dashboard
                    this.$router.push('/');
                }
            }
        },
        computed: {
            startDateTime() {
                let canBookToday = this.canBookToday();

                if (canBookToday) {
                    let hour = this.getNearestTime(this.now, 60) + this.minimumNotice;
                    return moment(this.now).set({hour: hour, minute: 0});
                } else {
                    return moment(this.now).add(1, 'days').set({hour: this.startOfDayHour, minute: 0});
                }
            }
        },
        created() {
            this.$eventHub.$on('datetime-change', this.onDateTimeChange);
        }
    }
</script>

<style lang="sass">
    .datetime-selector {
        padding-left: 0.2rem;
        padding-right: 0.2rem;
        &.is-selected:not([disabled]) {
            background-color: #00d1b2;
            p,
            .title,
            .subtitle {
                color: white;
            }
        }
    }

    @media screen and (min-width: 769px) {
        .control-label {
            text-align: left;
        }
        .control.is-horizontal > .control {
            flex-grow: 2;
        }
    }
</style>