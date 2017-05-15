<template>
  <div :class="animClasses">
    <div class="container small">
      <!-- progress indicator -->
      <ul class="signup_progress-indicator">
        <li class="signup_progress-step current"></li>
        <li class="signup_progress-step"></li>
        <li class="signup_progress-step"></li>
      </ul>

      <h1 class="header-xlarge">Choose your physician</h1>
      <p class="large">Tell us which type of integrative doctor you would like to partner with. We currently offer <strong>two types</strong> of doctors in your state.</p>
    </div>

    <div class="container large">
      <div class="signup-form-container large">
        <div class="flex-wrapper">
          <div class="input-wrap radio-block">
            <input
              type="radio"
              name="practitioner"
              id="naturopathic"
              class="radio_block input-hidden"
              value="naturopathic"
              v-model="practitioner"
              v-validate="'required'"
            />
            <label class="block" for="naturopathic">
              <div class="radio-block_container">
                <h2 class="header-large text-centered">Naturopathic Doctor</h2>
                <em class="tip text-centered">Here's an example...</em>
                <img src="/images/doctors/amanda.png">
                <div class="practitioner-main-content">
                  <p><strong>Dr. Amanda Frick, N.D.</strong> went to an accredited four-year naturopathic medical school and she's licensed by the state of California.</p>
                  <ul>
                    <li>Combines natural healing philosophies with the rigors of modern science.</li>
                    <li>Specializes in prevention, root cause analysis and holistic treatments.</li>
                    <li>Heavy training in nutrition, lab testing and supplementation.</li>
                  </ul>
                </div>
                <h3 class="text-centered price"><em class="bold">$150</em> per hour<em class="tip text-centered blue">(First time? Pick me!)</em></h3>
              </div>
            </label>
          </div>

          <div class="input-wrap radio-block">
            <input
              type="radio"
              name="practitioner"
              id="osteopathy"
              class="radio_block input-hidden"
              value="osteopathy"
              v-model="practitioner"
            />
            <label class="block" for="osteopathy">
              <div class="radio-block_container">
                <h2 class="header-large text-centered">Doctor of Osteopathy</h2>
                <em class="tip text-centered">Here's an example...</em>
                <img src="/images/doctors/rachel.png">
                <div class="practitioner-main-content">
                  <p><strong>Dr. Rachel West, D.O.</strong> is licensed to practice a full scope of medicine in all 50 states, equivalent to a medical doctor (MD).</p>
                  <ul>
                    <li>Receives more training than NDs or MDs in musculoskeletal systems (nerves, muscles and bones).</li>
                    <li>Also heavily trained in prevention, clinical nutrition, medical lab testing and neutraceuticals.</li>
                    <li>Utilizes a wide variety of therapies to treat unusual health conditions.</li>
                  </ul>
                </div>
                <h3 class="text-centered price"><em class="bold">$300</em> per hour</h3></li>
              </div>
            </label>
          </div>
        </div>

        <div v-show="errors.has('practitioner')" class="text-centered">
          <span class="error-text">{{ errors.first('practitioner') }}</span>
        </div>

        <div class="input-wrap text-centered">
            <p class="container medium">If this is your first time seeking advice for a specific ailment, we recommend the Naturopathic Doctor (ND). If you still need help deciding, <a href="https://blog.goharvey.com/comparing-naturopathic-doctors-and-medical-doctors-65300ba437c4" target="_blank">click here</a>.</p>
        </div>

        <div class="text-centered">
          <button class="button" @click.prevent="nextStep">Continue</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        title: 'Choose your physician',
        subtitle: 'Tell us which type of integrative doctor you would like to partner with. If this is your first time seeking advice for a specific ailment, we recommend a Naturopathic Doctor.',
        practitioner: this.$root.$data.schedule.practitioner || '',
        animClasses: {
          'anim-fade-slideup': true,
          'anim-fade-slideup-in': false,
        },
      }
    },
    methods: {
      nextStep() {
        this.$validator.validateAll().then(() => {
          this.$parent.practitioner = this.practitioner;
          this.$root.$data.schedule.practitioner = this.practitioner;
          this.getAvailability(this.practitioner);
        }).catch(() => {});
      },

      getAvailability(practitioner) {
        const practitioner_id = practitioner === 'osteopathy' ? 2 : 1; // Todo: Dynamically return best available practitioner id

        // let the parent know what practitioner has been selected
        this.$parent.practitioner = practitioner_id;

        axios.get(`api/v1/practitioners/${practitioner_id}?include=availability`)
          .then(response => {
            this.$parent.practitioner_availability = response.data.meta.availability;

            // since the availability is required for this process, let's block
            // next steps until we get a 200
            this.$parent.next();
          })
          .catch(error => {
            // Todo: Catch error
          });
      }
    },
    name: 'Practitioner',
    mounted() {
      if (this.$parent.env === 'prod') {
        this.$ma.trackEvent({
          action: 'View Select Practitioner',
          fb_event: 'ViewContent',
          category: 'clicks',
          properties: { laravel_object: Laravel.user },
          value: 'PageView',
        });
      }
      this.$eventHub.$emit('animate', this.animClasses, 'anim-fade-slideup-in', true, 300);
    },
    beforeDestroy() {
      this.$eventHub.$emit('animate', this.animClasses, 'anim-fade-slideup-in', false);
    }
  }
</script>

<style>

</style>
