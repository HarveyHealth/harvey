<template>
  <div v-show="validDate">
    <div :class="animClasses">
      <div class="container small confirmation">

        <img class="confirmation_calendar" src="/images/signup/calendar.png" alt="">

        <h1 class="header-xlarge">{{ title }}</h1>

        <p class="confirmation_date">
          <span class="confirmation_day">{{date.format('dddd')}}, {{date.format('MMMM')}} {{date.format('Do')}}</span> at <span class="confirmation_time">{{time}}</span>
          <!-- confirmation_calendar-add -->
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
        subtitle: 'We just sent you a text message and email confirmation — make sure you received them both. Please note, before talking with your doctor, you must complete our patient intake form (link below).',
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
      this.appointmentDate = moment(this.$root.initialAppointment.appointment_at);
      this.calendarSummary = `Appointment with ${this.appointmentInformation.attributes.practitioner_name}`;
      this.calendarStart = moment(this.appointmentDate).format('MM/DD/YYYY hh:mm A');
      this.calendarEnd = moment(this.appointmentDate).add(60, 'm').format('MM/DD/YYYY hh:mm A');
    },
    methods: {
      dispatchEvent() {

        if (this.env === 'prod') {

            this.$ma.trackEvent({
            action: 'IntakeQ Form Initiated',
            fb_event: 'ViewContent',
            category: 'clicks',
            properties: { laravel_object: Laravel.user }
          });
        }
      }
    },
    mounted() {
      // if state is lost, move into the dashboard
      if (!this.appointmentDate.isValid()) {
        window.location.href = '/dashboard';
      } else {
        this.validDate = true;
        this.$eventHub.$emit('animate', this.animClasses, 'anim-fade-in', true, 300);

        // A purchase event is typically associated with a specified product or product_group.
        // See https://developers.facebook.com/docs/ads-for-websites/pixel-troubleshooting#catalog-pair
        if (this.env === 'prod') {
          this.$ma.trackEvent({
            fb_event: 'Purchase',
            type: 'product',
            action: 'Appointment Scheduled',
            category: 'clicks',
            value: 50.00,
            currency: 'USD',
            properties: { laravel_object: Laravel.user }
          });
        }

        axios.patch(`api/v1/users/${this.$root.global.user.id}`, {
            first_name: this.$root.global.user.attributes.first_name,
            last_name: this.$root.global.user.attributes.last_name,
            phone: this.$root.global.user.attributes.phone
          })
          .then(response => {
              // phone, firstname, lastname updated
          })
          .catch(error => {
            this.responseErrors = error.response.data.errors;
          });

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

<style>

</style>
