<template>
  <div :class="containerClasses">
    <div class="success-wrapper mt-xl_lgH">
      <img class="success-icon" src="/images/signup/calendar.png" alt="">

      <h1 class="font-medium font-xlarge_md mt-md">{{ title }}</h1>

      <p class="font-base font-medium_md">
        <span class="confirmation_day">
          Dr. {{ $root.$data.signup.practitionerName }}, N.D.<br>
          {{ appointmentDate | toDate }}</span> at <span class="confirmation_time">{{ appointmentDate | toTime }} {{$root.addTimezone()}}
        </span>

        <div title="Add to Calendar" :class="{addeventatc: true, isVisible: calendarVisible, 'mt-sm': true}">
          Add to Calendar
          <span class="start">{{ calendarStart }}</span>
          <span class="end">{{ calendarEnd }}</span>
          <span class="timezone">{{ calendarZone }}</span>
          <span class="title">{{ calendarSummary }}</span>
          <span class="description">{{ calendarDescription }}</span>
          <span class="location">{{ calendarLocation }}</span>
          <span class="organizer">Harvey</span>
          <span class="organizer_email">support@goharvey.com</span>
          <span class="all_day_event">false</span>
          <span class="date_format">MM/DD/YYYY</span>
          <span class="client">ajiwVmWorzcyJqbpmmXE27705</span>
          <span class="addeventatc_icon" style="height: 0 !important; background: none !important;"></span>
        </div>
      </p>

      <p class="font-base" v-html="note"></p>

      <div class="text-centered mt-lg mt-xl_md">
        <a @click="dispatchEvent" :href="intakeUrl" class="button button--blue">Start Intake Form</a>
        <a href="/dashboard" class="button button--cancel">Go to Dashboard</a>
      </div>
    </div>
  </div>
</template>

<script>
import moment from 'moment';

export default {
  name: 'success',
  data() {
    return {
      containerClasses: {
        'anim-fade-slideup': true,
        'anim-fade-slideup-in': false,
        'container': true,
      },
      title: 'Your appointment is confirmed!',
      note: 'We will send you a few text and email reminders leading up to your appointment. Please note, you must complete our patient intake form (below) <strong>before</strong> talking with your doctor.',
      intakeUrl: `https://goharvey.intakeq.com/new/Qqy0mI/DpjPFg?harveyID=${Laravel.user.id}`,
      appointmentDate: this.$root.$data.signup.data.appointment_at,
      appointmentInformation: this.$root.$data.signup.data,
      validDate: false,
      env: this.$root.$data.environment,
      calendarVisible: false,
      calendarSummary: `Appointment with ${this.$root.$data.signup.practitionerName}`,
      calendarStart: moment.utc(this.appointmentDate).local().format('MM/DD/YYYY hh:mm A'),
      calendarEnd: moment.utc(this.appointmentDate).add(60, 'm').local().format('MM/DD/YYYY hh:mm A'),
      calendarZone: '',
      calendarLocation: '',
      calendarDescription: '',
    }
  },
  computed: {
    time() {
      const timeObject = this.appointmentDate.format('h:mm a');
      return timeObject;
    },
    date() {
      const dateObject = this.appointmentDate;
      return dateObject;
    }
  },
  methods: {
    dispatchEvent() {
      if (this.$root.$data.environment === 'production' || this.$root.$data.environment === 'prod') {
          this.$ma.trackEvent({
            action: 'IntakeQ Form Initiated',
            fb_event: 'ViewContent',
            category: 'clicks',
            properties: { laravel_object: Laravel.user }
        });
      }
    }
  },
  filters: {
    toDate(date) {
      return moment.utc(date).local().format('dddd, MMMM Do');
    },
    toTime(date) {
      return moment.utc(date).local().format('h:mm a');
    }
  },
  mounted () {
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', true, 300);
    // A purchase event is typically associated with a specified product or product_group.
    // See https://developers.facebook.com/docs/ads-for-websites/pixel-troubleshooting#catalog-pair
    if (this.$root.$data.environment === 'production' || this.$root.$data.environment === 'prod') {
      this.$ma.trackEvent({
          fb_event: 'PageView',
          type: 'product',
          category: 'clicks',
          properties: { laravel_object: Laravel.user }
      });
      this.$ma.trackEvent({
        fb_event: 'Purchase',
        type: 'product',
        action: 'Complete Purchase',
        category: 'clicks',
        value: 50.00,
        currency: 'USD',
        properties: { laravel_object: Laravel.user }
      });
      ga('send', {
        hitType: "event",
        eventCategory: "clicks",
        eventAction: "Confirm Appointment",
        eventLabel: null,
          eventValue: 50,
          hitCallback: null,
          userId: null
      });
    }

    // From https://www.addevent.com/buttons/add-to-calendar
    // Has to be added on component mount because it needs to be able to find
    // the corresponding button in the DOM.
    (function (context) {
      var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
      s.type = 'text/javascript';
      s.charset = 'UTF-8';
      s.async = true;
      s.src = ('https:' == window.location.protocol ? 'https' : 'http')+'://addevent.com/libs/atc/1.6.1/atc.min.js';
      var h = d[g]('body')[0];
      h.appendChild(s)
      s.onload = () => context.calendarVisible = true;
    })(this);
  },
  beforeDestroy() {
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', false);
  }
}
</script>
