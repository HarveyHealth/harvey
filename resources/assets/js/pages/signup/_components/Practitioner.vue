<template>
  <div>
    <div class="container small">
      <!-- progress indicator -->
      <ul class="signup_progress-indicator">
        <li class="signup_progress-step current"></li>
        <li class="signup_progress-step"></li>
        <li class="signup_progress-step"></li>
      </ul>

      <h1 class="header-xlarge">Choose your practitioner</h1>
      <p class="large">Tell us which type of integrative doctor you would like to partner with. We currently offer <strong>two</strong> types of doctors in your state.</p>
    </div>

    <div class="container large">
      <div class="signup-form-container large">
        <div class="flex-wrapper">
          <div class="input-wrap radio-block">
            <input
              type="radio" name="practitioner"
              id="naturopathic" class="radio_block input-hidden" value="naturopathic" v-model="practitioner" @change="checkPractitioner" />
            <label class="block" for="naturopathic">
              <div class="radio-block_container">
                <h2 class="header-large text-centered">Naturopathic Doctor (ND)</h2>
                <p class="practitioner-main-content text-centered">Naturopathic physicians combine natural healing philosophies with the rigors of modern science. NDs focus on holistic, proactive prevention and comprehensive diagnosis and treatment. They receive heavier training in medical school in clinical nutrition and supplementation and are therefore more equipped than MDs to provide nutrition coaching, lifestyle counseling and homeopathic treatments.</li>
                </ul>
                <h3 class="text-centered"><em class="bold">$150</em> per hour</h3>
              </div>
            </label>
          </div>

          <div class="input-wrap radio-block">
            <input
              type="radio" name="practitioner"
              id="osteopathy" class="radio_block input-hidden" value="osteopathy" v-model="practitioner" @change="checkPractitioner" />
            <label class="block" for="osteopathy">
              <div class="radio-block_container">
                <h2 class="header-large text-centered">Doctor of Osteopathy (DO)</h2>
                <p class="practitioner-main-content text-centered">Osteopathic physicians are licensed to practice full scope of medicine in all 50 states but are typically more focused on holistic and integrative medicine than their MD counterparts. DOs receive up to 500 additional hours of training in musculoskeletal systems — your body's interconnected system of nerves, muscles and bones. DOs utilize the latest advances in medical lab testing and focus on prevention.</p>
                <h3 class="text-centered"><em class="bold">$300</em> per hour</h3>
              </div>
            </label>
          </div>
        </div>

          <div class="input-wrap text-centered">
              <label class="container small">If this is your first time seeking advice for a specific ailment, we recommend the Naturopathic Doctor (ND). If you still need help deciding, <a href="https://blog.goharvey.com/comparing-naturopathic-doctors-and-medical-doctors-65300ba437c4" target="_blank">click here</a>.</label>
          </div>

        <div class="text-centered">
          <button class="button" :disabled="!validated" @click.prevent="nextStep">Continue</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        title: 'Choose your practitioner',
        subtitle: 'Tell us which type of integrative doctor you would like to partner with. If this is your first time seeking advice for a specific ailment, we recommend a Naturopathic Doctor.',
        practitioner: '',
        validated: false,
      }
    },
    methods: {
      checkPractitioner(e) {
        this.practitioner = e.currentTarget.value;
        this.validated = true;
      },

      nextStep() {
        if ((this.practitioner != null || this.practitioner != '') && this.validated) {
          this.$parent.next();
        }
      }
    },
    name: 'Practitioner'
  }
</script>

<style>

</style>
