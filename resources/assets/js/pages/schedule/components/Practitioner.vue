<template>
  <div :class="animClasses">
    <div class="container small">
      <ul class="signup_progress-indicator">
        <li class="signup_progress-step current"></li>
        <li class="signup_progress-step"></li>
        <li class="signup_progress-step"></li>
      </ul>
      <div class="guide-block">
        <h1 class="header-xlarge">Choose your physician</h1>
        <p class="large">Tell us which type of integrative doctor you would like to partner with. We currently offer <strong>two types</strong> of doctors in your state.</p>
      </div>
    </div>

    <div class="container large">
      <div class="signup-form-container large">
        <div class="flex-wrapper">

          <div class="input-wrap radio-block">
            <input
              type="radio"
              name="practitioner"
              id="osteopathy"
              class="radio_block input-hidden"
              value="osteopathy"
              v-model="practitioner"
            />
            <label class="block doctor-label" for="osteopathy">
              <div class="radio-block_container">
                <h2 class="header-large text-centered">Functional M.D. or D.O.</h2>
                <em class="tip text-centered">Functional Medical Doctor or Doctor of Osteopathy</em>
                <div class="practitioner-main-content">
                  <ul>
                    <li>4-year medical school</li>
                    <li>Medical board exam</li>
                    <li>State medical license</li>
                    <li>Can order all lab tests</li>
                    <li>Can prescribe all scheduled drugs (I-V) and professional grade supplements</li>
                    <li>Prevention focused</li>
                    <li>Holistic approach</li>
                  </ul>
                </div>
                <h3 class="text-centered price"><em class="bold">$300</em> per hour</h3></li>
              </div>
            </label>
          </div>

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
            <label class="block doctor-label" for="naturopathic">
              <div class="radio-block_container">
                <h2 class="header-large text-centered">Naturopathic Doctor</h2>
                <em class="tip text-centered">Board-Certified Naturopathic Doctor (N.D.)</em>
                <div class="practitioner-main-content">
                    <ul>
                      <li>4-year medical school</li>
                      <li>Medical board exam</li>
                      <li>State medical license</li>
                      <li>3-7 years residency</li>
                      <li>Can order all lab tests</li>
                      <li>Can prescribe schedule III-V drugs and professional grade supplements</li>
                      <li>Nutrition expertise</li>
                    </ul>
                </div>
                <h3 class="text-centered price"><em class="bold">$150</em> per hour</h3>
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
  import transformAvailability from '../../../utils/methods/transformAvailability';

  export default {
    data() {
      return {
        title: 'Choose your physician',
        subtitle: 'Tell us which type of integrative doctor you would like to partner with. If this is your first time seeking advice for a specific ailment, we recommend a Naturopathic Doctor.',
        practitioner: this.$parent.practitioner || '',
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
          this.getAvailability(this.practitioner);
        }).catch(() => {});
      },

      getAvailability(practitioner) {
        // !!
        // !! Hardcoded for now. But this will need to be updated once more doctors get invovled
        // !!
        const practitioner_id = practitioner === 'osteopathy' ? 2 : 1;

        this.$root.initialAppointment.practitioner_id = practitioner_id;

        axios.get(`api/v1/practitioners/${this.$root.initialAppointment.practitioner_id}?include=availability`)
          .then(response => {
            this.$parent.practitioner_availability = response.data.meta.availability;
            this.$parent._availability = transformAvailability(response.data.meta.availability);

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
      this.$eventHub.$emit('animate', this.animClasses, 'anim-fade-slideup-in', true, 300);
    },
    beforeDestroy() {
      this.$eventHub.$emit('animate', this.animClasses, 'anim-fade-slideup-in', false);
    }
  }
</script>

<style>

</style>
