<template>
  <div :class="containerClasses" v-if="!$root.$data.signup.completedSignup">
    <h1 v-if="$root.$data.global.loadingPractitioners" class="text-centered">
      <LoadingBubbles :style="{ width: '26px', fill: '#555' }" />
      <div>Loading practitioners</div>
    </h1>
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
          <div :class="{ 'practitioner-wrapper': true, active: dr.id === store.signup.data.practitioner_id }" v-for="dr in practitioners" tabindex="0" @click="select(dr)">
            <div class="practitioner-bg" :style="{ backgroundImage: 'url(' + dr.info.background_picture_url + ')' }"></div>
            <img v-if="dr.info.picture_url" class="practitioner-avatar" :src="dr.info.picture_url" />
            <h3 v-if="dr.name" class="practitioner-name text-centered">{{ dr.name }}, ND<!--{{ dr.info.type_name }}--></h3>
            <p v-if="dr.info.license_number" class="practitioner-license text-centered">License {{ dr.info.license_number }} {{ dr.info.license_state }}</p>
            <div class="practitioner-info-wrapper">
              <p v-if="dr.info.description">{{ dr.info.description }}</p>
              <hr class="practitioner-divider" />
              <ul class="practitioner-info">
                <li v-if="dr.info.graduated_at"><span>Graduated:</span> {{ dr.info.graduated_at.date | year }}</li>
                <li v-if="dr.info.school"><span>Degree:</span> {{ dr.info.school }}</li>
                <li v-if="dr.info.specialty"><span>Specialties:</span> {{ dr.info.specialty | specialty }}</li>
              </ul>
              <hr class="practitioner-divider" />
              <p class="practitioner-rate text-centered"><span>$150</span>/hour</p>
            </div>
          </div>
        </div>
        <p class="error-text" v-html="errorText" v-show="errorText"></p>
        <div class="text-centered" ref="button">
          <button class="button button--blue" style="width: 160px" :disabled="isProcessing" @click="getAvailability(store.signup.data.practitioner_id)">
            <span v-if="!isProcessing">Continue</span>
            <LoadingBubbles v-else-if="isProcessing" :style="{ width: '12px', fill: 'white' }" />
            <i v-else-if="isComplete" class="fa fa-check"></i>
          </button>
        </div>
      </div>
    </template>
  </div>
</template>

<script>
import moment from 'moment';

import LoadingBubbles from '../../../commons/LoadingBubbles.vue';
import StagesNav from '../util/StagesNav.vue';
import transformAvailability from '../../../utils/methods/transformAvailability';

export default {
  name: 'practitioner',
  components: {
    LoadingBubbles,
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
    specialty(value) {
      return value.replace(/[\[\]"]*/g, '');
    },
    year(value) {
      return moment(value).format('YYYY');
    }
  },
  computed: {
    // Grab the first two practitioners for now
    practitioners() {
      return this.store.global.practitioners.length
        ? [this.store.global.practitioners[0], this.store.global.practitioners[1]]
        : [];
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
    select(dr) {
      this.$refs.button.scrollIntoView();
      this.store.signup.data.practitioner_id = dr.id;
      this.store.signup.practitionerName = dr.info.name;
      this.store.signup.practitionerState = dr.info.license_state;
      this.errorText = null;
    }
  },
  mounted () {
    this.$root.toDashboard();
    this.$root.$data.signup.visistedStages.push('practitioner');
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', true, 300);
  },
  beforeDestroy() {
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', false);
  }
}
</script>
