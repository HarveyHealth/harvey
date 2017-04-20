<template>
  <div v-show="validDate">
    <div class="container small confirmation">

      <img class="confirmation_calendar" src="/images/signup/calendar.png" alt="">

      <h1 class="header-xlarge">{{ title }}</h1>

      <p class="confirmation_date"><span class="confirmation_day">{{date.format('dddd')}}, {{date.format('MMMM')}} {{date.format('Do')}}</span> at <span class="confirmation_time">{{time}}</span>
        <!-- <button class="confirmation_calendar-add"><img src="/images/signup/calendar-add.png" alt=""></button> -->
      </p>

      <!-- <a class="confirmation_reschedule" href="#">Reschedule</a> -->

      <p class="confirmation_text large">{{ subtitle }}</p>
      <div class="text-centered">
        <a @click="dispatchEvent" :href="intakeUrl" class="button">Start Intake Form</a>
      </div>
    </div>
  </div>
</template>

<script>
  import moment from 'moment';

  export default {
    name: 'Confirmation',
    data() {
      return {
        title: 'Your appointment is confirmed!',
        subtitle: 'We just sent you a text message and email confirmation — make sure you received them both. Please note, before talking with your doctor, you must complete our patient intake form (link below).',
        intakeUrl: `https://goharvey.intakeq.com/new/Qqy0mI/DpjPFg?harveyID=${Laravel.user.id}`,
        appointmentDate: null,
        validDate: false,
        env: this.$root.$data.environment,
      }
    },
    created() {
      this.appointmentDate = moment(this.$root.$data.sharedState.appointmentDate);
    },
    methods: {
      dispatchEvent() {

        if (this.env === 'prod') {
          this.$ma.trackEvent({action: 'IntakeQ Form Initiated', category: 'clicks', properties: {laravel_object: Laravel.user}})
        }
      }
    },
    mounted() {
      // if state is lost, move into the dashboard
      if (!this.appointmentDate.isValid()) {
        window.location.href = '/dashboard';
      } else {
        this.validDate = true;

        if (this.env === 'prod') {
          this.$ma.trackEvent({action: 'Appointment Scheduled', category: 'clicks', properties: {laravel_object: Laravel.user}})
        }
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
