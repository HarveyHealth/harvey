<template>
    <form
        role="form"
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
                <h2 class="title is-4">Details</h2>
            </div>
            <div class="control has-icon">
                <textarea
                    v-model="form.details"
                    name="details"
                    :class="['textarea', {'is-danger' : form.errors.has('details')}]"
                    placeholder="Explain how we can help you"
                    :autofocus="form.errors.has('details')"
                    required
                >
                </textarea>
                <template v-if="form.errors.has('details')">
                    <span class="icon is-small align-right is-danger"><i class="fa fa-warning"></i></span>
                    <span class="help is-danger" v-text="form.errors.get('details')"></span>
                </template>
            </div>
        </div>

        <p v-if="includeCta" class="control is-clearfix hero-buttons">
            <button
                type="submit"
                class="button is-medium is-primary"
                :disabled="form.errors.any()"
            >Schedule</button>
        </p>
    </form>
</template>

<script>
import moment from 'moment';
import DatePicker from './components/DatePicker.vue';
import TimePicker from './components/TimePicker.vue';

export default {
    name: 'new-appointment',
    props: {
        user: Object,
        includeCta: Boolean,
        form: Object
    },
    data() {
        return {
            // form: new Form({
            //     selectedDate: '',
            //     selectedTime: '',
            //     details: '',
            // }),
            now: moment(),
            startOfDayHour: 9,
            endOfDayHour: 18,
            maximumDays: 7,
            minimumNotice: 0, // hours
            duration: 1 // hours
        };
    },
    components: {
        DatePicker,
        TimePicker
    },
    methods: {
        canBookToday() {
            let acceptableTime = moment(this.now).add(this.minimumNotice, 'hours');
            let endOfDayTime = moment(this.now).set({hour: this.endOfDayHour, minute: 0, second:0, millisecond:0}).subtract(this.duration, 'hours');

            return acceptableTime <= endOfDayTime;
        },
        getNearestTime(time, interval) {
            var minutes = Math.ceil(Math.max(1, time.minutes()) / interval) * interval,
                hours = time.hours();

            if (minutes == 60) {
                hours++;
                minutes = 0;
                if (hours >= 24) {
                    hours = hours - 24;
                }
            }
            return hours;
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
            // this.$eventHub.$emit('mixpanel', "New Appointment Created");
        }
    },
    computed: {
        startDateTime() {
            let canBookToday = this.canBookToday();

            if (canBookToday) {
                let hour = this.getNearestTime(this.now, 60) + this.minimumNotice;

                return this.now.set({hour: hour, minute: 0, second:0, millisecond:0}).utc();
            } else {
                return this.now.add(1, 'days').set({hour: this.startOfDayHour, minute: 0, second:0, millisecond:0}).utc();
            }
        }
    },
    created() {
        this.$eventHub.$on('datetime-change', this.onDateTimeChange);
    }
};
</script>
