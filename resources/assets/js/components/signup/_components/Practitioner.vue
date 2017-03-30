<template>
  <div>
    <div>
      <!-- progress indicator -->
      <ul class="signup_progress-indicator">
        <li class="signup_progress-step"></li>
        <li class="signup_progress-step current"></li>
        <li class="signup_progress-step"></li>
        <li class="signup_progress-step"></li>
      </ul>

      <h1 class="header-xlarge">Choose your practioner</h1>
      <p class="large">Tell us which type of integrative doctor you would like to partner with. If this is your first time seeking advice for a specific ailment, we recommend a Naturopathic Doctor.</p>
    </div>

    <div class="container">
      <div class="signup-form-container large">

          <div class="flex-wrapper">
            <div class="input-wrap radio-block">
              <input
                type="radio" name="practitioner"
                id="naturopathic" class="radio_block input-hidden" />
              <label class="block" for="naturopathic">
                <div class="radio-block_container">
                  <h2 class="header-large text-centered">Naturopathic Doctor</h2>
                  <p class="practitioner-main-content text-centered">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet.</p>
                  <ul class="practitioner-features">
                    <li>One</li>
                    <li>Two</li>
                    <li>Three</li>
                  </ul>
                  <p class="text-centered"><em class="bold">$150</em> per hour</p>
                </div>
              </label>
            </div>

            <div class="input-wrap radio-block">
              <input
                type="radio" name="practitioner"
                id="osteopathy" class="radio_block input-hidden" />
              <label class="block" for="osteopathy">
                <div class="radio-block_container">
                  <h2 class="header-large text-centered">Doctor of Osteopathy</h2>
                  <p class="practitioner-main-content text-centered">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet.</p>
                  <ul class="practitioner-features">
                    <li>One</li>
                    <li>Two</li>
                    <li>Three</li>
                  </ul>
                  <p class="text-centered"><em class="bold">$150</em> per hour</p>
                </div>
              </label>
            </div>
          </div>

          <div class="text-centered">
            <input type="submit" class="btn btn-default" value="Continue">
          </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        title: 'Choose your practioner',
        subtitle: 'Tell us which type of integrative doctor you would like to partner with. If this is your first time seeking advice for a specific ailment, we recommend a Naturopathic Doctor.'
      }
    },
    name: 'Practitioner',
    props: ['nextStep']
  }
</script>

<style>

</style>
