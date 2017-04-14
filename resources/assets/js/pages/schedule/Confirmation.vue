<template>
  <div>
    <div class="container small confirmation">

      <img class="confirmation_calendar" src="/images/signup/calendar.png" alt="">

      <h1 class="header-xlarge">{{ title }}</h1>

      <p class="confirmation_date"><span class="confirmation_day">Thursday, March 30th</span> at <span class="confirmation_time">5:00pm</span> <button class="confirmation_calendar-add"><img src="/images/signup/calendar-add.png" alt=""></button></p>
      <a class="confirmation_reschedule" href="#">Reschedule</a>
      <p class="confirmation_text large">{{ subtitle }}</p>
      <div class="text-centered">
        <a href="https://goharvey.intakeq.com/new/Qqy0mI/DpjPFg?harveyID=" class="button">Start Intake Form</a>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    props: ['appointmentDate'],
    data() {
      return {
        title: 'Your appointment is confirmed!',
        subtitle: 'We just sent you a text message and email confirmation — make sure you received them both. Please note, before talking with your doctor, you must complete our patient intake form (link below).'
      }
    },
    name: 'Confirmation'
  }
</script>

<style>

</style>
