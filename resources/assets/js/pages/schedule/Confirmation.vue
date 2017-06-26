<template>
  <div v-show="validDate">
    <div :class="animClasses">
      <div class="container small confirmation">

        <img class="confirmation_calendar" src="/images/signup/calendar.png" alt="">

        <h1 class="header-xlarge">{{ title }}</h1>

        <p class="confirmation_date">
          <span class="confirmation_day">
            {{ appointmentDate | toDate }}</span> at <span class="confirmation_time">{{ appointmentDate | toTime }} {{$root.addTimezone()}}
          </span>

          <div title="Add to Calendar" :class="{addeventatc: true, isVisible: calendarVisible}">
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
          </div>
        </p>

        <p class="confirmation_text large">{{ subtitle }}</p>

        <div class="text-centered">
          <a @click="dispatchEvent" :href="intakeUrl" class="button">Start Intake</a>
          <a href="/dashboard" class="button is-outlined dashboard">Dashboard</a>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
  import moment from 'moment-timezone';

  export default {
    name: 'Confirmation',
    data() {
      return {
        title: 'Your appointment is confirmed!',
        subtitle: 'Please note, your doctor requires you to fill out a patient intake form before your first consultation. This will take about 20 minutes. The link to the form is below and in your email confirmation.',
        intakeUrl: `https://goharvey.intakeq.com/new/Qqy0mI/DpjPFg?harveyID=${Laravel.user.id}`,
        appointmentDate: null,
        appointmentInformation: null,
        validDate: false,
        env: this.$root.$data.environment,
        calendarVisible: false,
        calendarSummary: '',
        calendarStart: '',
        calendarEnd: '',
        calendarZone: '',
        calendarLocation: '',
        calendarDescription: '',
        animClasses: {
          'anim-fade': true,
          'anim-fade-in': false,
        },
      }
    },
    created() {
      this.appointmentInformation = this.$root.appointmentData.data;
      this.appointmentDate = this.$root.initialAppointment.appointment_at;
      this.calendarSummary = `Appointment with ${this.appointmentInformation.attributes.practitioner_name}`;
      this.calendarStart = moment.utc(this.appointmentDate).local().format('MM/DD/YYYY hh:mm A');
      this.calendarEnd = moment.utc(this.appointmentDate).add(60, 'm').local().format('MM/DD/YYYY hh:mm A');
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
    mounted() {
      // if state is lost, move into the dashboard
      if (!moment(this.appointmentDate).isValid()) {
        window.location.href = '/dashboard';
      } else {

        this.validDate = true;
        this.$eventHub.$emit('animate', this.animClasses, 'anim-fade-in', true, 300);

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
            eventAction: "Sign-up For Account", 
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
    }
  }
</script>
