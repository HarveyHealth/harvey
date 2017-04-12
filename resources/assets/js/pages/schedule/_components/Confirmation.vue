<template>
  <div>
    <div class="container small confirmation">

      <img class="confirmation_calendar" src="/images/signup/calendar.png" alt="">

      <h1 class="header-xlarge">{{ title }}</h1>

      <p class="confirmation_date"><span class="confirmation_day">Thursday, March 30th</span> at <span class="confirmation_time">5:00pm</span> <button class="confirmation_calendar-add"><img src="/images/signup/calendar-add.png" alt=""></button></p>
      <a class="confirmation_reschedule" href="#">Reschedule</a>

      <p class="confirmation_text large">{{ subtitle }}</p>

    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        title: 'Your appointment is confirmed!',
        subtitle: 'We just sent you a text message and email — make sure you receive them both. You must complete the following patient intake form before talking with your doctor.'
      }
    },
    name: 'Confirmation'
  }
</script>

<style>

</style>
