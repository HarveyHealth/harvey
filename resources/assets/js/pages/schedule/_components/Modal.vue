<template>
  <div>

    <div class="modal-wrapper">
      <div class="modal">
        <h3 class="modal-header">You are about to leave Harvey!</h3>
        <p>Your patient intake will be conducted by a third-party HIPAA-compliant EMR provider called “IntakeQ”.</p>
        <p>When prompted, enter your full name and the same email you used to sign up for Harvey. You can always close the form and come back to it later.</p>
        <div class="modal-button-container">
          <button class="button" @click="nextStep('confirmation')">Start Intake</button>
        </div>
      </div>
    </div>

    <div class="container small">

      <!-- progress indicator -->
      <ul class="signup_progress-indicator">
        <li class="signup_progress-step"></li>
        <li class="signup_progress-step"></li>
        <li class="signup_progress-step"></li>
        <li class="signup_progress-step current"></li>
      </ul>

      <h1 class="header-xlarge">{{ title }}</h1>
      <p class="large">{{ subtitle }}</p>
    </div>
    <div class="container large">
      <div class="signup-form-container large">
        <div class="flex-wrapper">
          <div class="input-wrap calendar-block">
            <div class="calendar-block_container">
              <h2 class="header-large text-centered">Choose Date</h2>
              <div class="calendar-week-container">
                <div class="calendar-week-container_title-wrapper">
                  <h3 class="calendar-week-container_title">This week</h3>
                  <span class="calendar-week-container_date">March 27 - April 2nd</span>
                </div>
                <ul class="calendar-week-container_days-wrapper">
                  <li class="calendar-item"><button class="calendar-item_link">Mon</button></li>
                  <li class="calendar-item"><button class="calendar-item_link">Tue</button></li>
                  <li class="calendar-item"><button class="calendar-item_link">Wed</button></li>
                  <li class="calendar-item"><button class="calendar-item_link">Thu</button></li>
                  <li class="calendar-item"><button class="calendar-item_link">Fri</button></li>
                  <li class="calendar-item"><button class="calendar-item_link">Sat</button></li>
                  <li class="calendar-item selected"><button class="calendar-item_link">Sun</button></li>
                </ul>
              </div>
              <div class="calendar-week-container">
                <div class="calendar-week-container_title-wrapper">
                  <h3 class="calendar-week-container_title">Next week</h3>
                  <span class="calendar-week-container_date">March 27 - April 2nd</span>
                </div>
                <ul class="calendar-week-container_days-wrapper">
                  <li class="calendar-item"><button class="calendar-item_link">Mon</button></li>
                  <li class="calendar-item"><button class="calendar-item_link">Tue</button></li>
                  <li class="calendar-item"><button class="calendar-item_link">Wed</button></li>
                  <li class="calendar-item"><button class="calendar-item_link">Thu</button></li>
                  <li class="calendar-item"><button class="calendar-item_link">Fri</button></li>
                  <li class="calendar-item"><button class="calendar-item_link">Sat</button></li>
                  <li class="calendar-item selected"><button class="calendar-item_link">Sun</button></li>
                </ul>
              </div>
            </div>
          </div>

          <div class="input-wrap calendar-block">
            <div class="calendar-block_container">
              <h2 class="header-large text-centered">Choose Time</h2>
              <div class="calendar-week-container">
                <ul class="calendar-week-container_days-wrapper">
                  <li class="calendar-item"><button class="calendar-item_link">9a</button></li>
                  <li class="calendar-item"><button class="calendar-item_link">10a</button></li>
                  <li class="calendar-item"><button class="calendar-item_link">11a</button></li>
                  <li class="calendar-item"><button class="calendar-item_link">12p</button></li>
                  <li class="calendar-item"><button class="calendar-item_link">1p</button></li>
                  <li class="calendar-item"><button class="calendar-item_link">2p</button></li>
                  <li class="calendar-item selected"><button class="calendar-item_link">3p</button></li>
                </ul>
              </div>
              <p class="text-centered small">Time Zone: PST</p>
            </div>
          </div>
        </div>
        <div class="text-centered">
          <button class="button">Continue</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        title: 'Choose date and time',
        subtitle: 'Tell us the best date and time you would like to schedule a phone consultation with a doctor. Remember, this is not in-person.'
      }
    },
    name: 'Modal',
    mounted() {
      this.$ma.trackEvent({value: 'PageView'})
    }
  }
</script>

<style>

</style>
