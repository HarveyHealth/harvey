<template>
  <div :class="containerClasses" v-if="!$root.$data.signup.completedSignup">
    <h3 v-if="$root.$data.global.loadingPractitioners" class="text-centered">
      <LoadingGraphic :size="26" :fill="'#555'" />
      <div>Loading Practitioners...</div>
    </h3>
    <div v-else-if="!$root.$data.global.loadingPractitioners && !practitioners.length" :style="{ 'max-width': '500px', 'margin': '0 auto' }">
      <i class="fa fa-error"></i>
      <p class="error-text">Oops! Sorry, it looks like there was a problem getting practitioner data. Please call us at <a href="tel:8006909989">800-690-9989</a> to speak with our Customer Support.</p>
    </div>
    <template v-else>
      <div class="signup-stage-instructions">
        <StagesNav :current="'practitioner'" />
        <h2>Choose Your Practitioner</h2>
        <p>The Naturopathic Doctors below are licensed and available to work with patients in your state. Please select the doctor you prefer.</p>
      </div>
      <div class="signup-container signup-stage-container">
        <div class="signup-practitioner-wrapper cf">

          <div class="practitioner-wrapper">
            <h3 class="signup-section-header">Available Doctors</h3>
            <div class="signup-practitioner-selector-wrap" v-for="(dr, index) in practitioners">
              <button :class="{ 'signup-practitioner-selector': true, 'active': index === selected }" @click="select(dr, index)">
                <img :src="dr.info.picture_url" v-if="dr.info.picture_url" />
              </button>
            </div>
          </div>

          <div class="practitioner-wrapper">
            <div v-if="hasSelection">
              <div class="practitioner-bg" :style="{ backgroundImage: 'url(' + practitioners[selected].info.background_picture_url + ')' }"></div>
              <img v-if="practitioners[selected].info.picture_url" class="practitioner-avatar" :src="practitioners[selected].info.picture_url" />
              <h3 v-if="practitioners[selected].name" class="practitioner-name text-centered">{{ practitioners[selected].name }}, ND</h3>
              <p v-if="practitioners[selected].info.license_number" class="practitioner-license text-centered">License {{ practitioners[selected].info.license_number }}</p>
              <div class="practitioner-info-wrapper">
                <p v-if="practitioners[selected].info.description">{{ practitioners[selected].info.description }}</p>
                <hr class="practitioner-divider" />
                <ul class="practitioner-info">
                  <li v-if="practitioners[selected].info.graduated_at"><span>Graduated:</span> {{ practitioners[selected].info.graduated_at.date | year }}</li>
                  <li v-if="practitioners[selected].info.school"><span>Degree:</span> {{ practitioners[selected].info.school }}</li>
                  <li v-if="practitioners[selected].info.specialty"><span>Specialties:</span> {{ practitioners[selected].info.specialty | specialty }}</li>
                </ul>
                <hr class="practitioner-divider" />
                <p class="practitioner-rate text-centered"><span>$150</span>/hour</p>
              </div>
            </div>
          </div>

        </div>
        <p class="error-text" v-html="errorText" v-show="errorText"></p>
        <p class="text-centered" v-if="hasSelection">Your choice is <span class="selected-practitioner">{{ practitioners[selected].name }}, ND</span></p>
        <div class="text-centered" ref="button">
          <button class="button button--blue" style="width: 160px" :disabled="isProcessing" @click="getAvailability(store.signup.data.practitioner_id)">
            <span v-if="!isProcessing">Continue</span>
            <LoadingGraphic v-else-if="isProcessing" :size="12" />
            <i v-else-if="isComplete" class="fa fa-check"></i>
          </button>
        </div>
      </div>
    </template>
  </div>
</template>

<script>
import moment from 'moment';

import LoadingGraphic from '../../../commons/LoadingGraphic.vue';
import StagesNav from '../util/StagesNav.vue';
import transformAvailability from '../../../utils/methods/transformAvailability';

export default {
  name: 'practitioner',
  components: {
    LoadingGraphic,
    StagesNav,
  },
  data() {
    return {
      containerClasses: {
        'anim-fade-slideup': true,
        'anim-fade-slideup-in': false,
        'container': true,
      },
      errorText: null,
      isProcessing: false,
      store: this.$root.$data,
    }
  },
  filters: {
    specialty(list) {
      return list.join(', ');
    },
    year(value) {
      return moment(value).format('YYYY');
    }
  },
  // This is for when the component loads before the practitioner list has finished loading
  watch: {
    practitioners(list) {
      if (list.length) {
        this.select(list[0], 0, true);
      }
    }
  },
  computed: {
    hasSelection() {
      return this.selected !== null;
    },
    // Grab up to 8 practitioners
    practitioners() {
      return this.store.global.practitioners.slice(0, 8);
    },
    selected() {
      return this.store.signup.selectedPractitioner;
    },
    nextStage() {
      return Laravel.user.phone_verified_at
        ? 'schedule'
        : 'phone';
    }
  },
  methods: {
    getAvailability(id) {
      this.isProcessing = true;
      if (!this.store.signup.data.practitioner_id) {
        this.errorText = 'Please select a practitioner by clicking their box.';
        this.isProcessing = false;
        return;
      }
      this.$root.getAvailability(id, response => {
        this.store.signup.availability = transformAvailability(response.data.meta.availability, Laravel.user.user_type);
        if (!this.store.signup.availability.length) {
          this.errorText = 'Unfortunately, we don\'t have any availability for that doctor in the next month, please choose another doctor. If you\'re stuck, give us a call at <a href="tel:8006909989">800-690-9989</a>.';
          this.isProcessing = false;
        } else {
          this.$router.push({ name: this.nextStage, path: `/${this.nextStage}` });
        }
      });
    },
    select(dr, index, noScroll) {
      // We've added noScroll for the initial selection of the first practitioner.
      // The ref hasn't registered yet so it throws a console error
      const shouldScroll = !noScroll || false;
      if (shouldScroll) this.$refs.button.scrollIntoView();
      this.store.signup.selectedPractitioner = index;
      this.store.signup.data.practitioner_id = dr.id;
      this.store.signup.practitionerName = dr.info.name;
      this.store.signup.practitionerState = dr.info.license_state;
      this.errorText = null;
    }
  },
  mounted () {
    this.$root.toDashboard();
    this.$root.$data.signup.visistedStages.push('practitioner');
    // This is for when the component loads after the practitioners had loaded
    if (this.practitioners.length) this.select(this.practitioners[0], 0, true);
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', true, 300);
  },
  beforeDestroy() {
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', false);
  }
}
</script>
