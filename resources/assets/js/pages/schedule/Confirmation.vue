<template>
  <div>
    <div class="container small confirmation">

      <img class="confirmation_calendar" src="/images/signup/calendar.png" alt="">

      <h1 class="header-xlarge">{{ title }}</h1>

      <p class="confirmation_date"><span class="confirmation_day">{{date.format('dddd')}}, {{date.format('MMMM')}} {{date.format('Do')}}</span> at <span class="confirmation_time">{{time}}</span>
        <!-- <button class="confirmation_calendar-add"><img src="/images/signup/calendar-add.png" alt=""></button> -->
      </p>

      <!-- <a class="confirmation_reschedule" href="#">Reschedule</a> -->

      <p class="confirmation_text large">{{ subtitle }}</p>
      <div class="text-centered">
        <a :href="intakeUrl" class="button">Start Intake Form</a>
      </div>
    </div>
  </div>
</template>

<script>
  import moment from 'moment';

  export default {
    name: 'Confirmation',
    props: ['appointmentDate'],
    data() {
      return {
        title: 'Your appointment is confirmed!',
        subtitle: 'We just sent you a text message and email confirmation — make sure you received them both. Please note, before talking with your doctor, you must complete our patient intake form (link below).',
        intakeUrl: `https://goharvey.intakeq.com/new/Qqy0mI/DpjPFg?harveyID=${Laravel.user.id}`,
      }
    },
    computed: {
      time() {
        const timeObject = moment(this.appointmentDate).format('HH:mm a');
        return timeObject;
      },

      date() {
        // console.log(moment('2017-04-17 07:00:00').format('MMMM'));
        const dateObject = moment(this.appointmentDate);
        return dateObject;
      }
    }
  }
</script>

<style>

</style>
