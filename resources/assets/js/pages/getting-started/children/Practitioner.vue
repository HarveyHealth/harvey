<template>
  <div :class="containerClasses" v-if="!$root.$data.signup.completedSignup">
    <div class="signup-stage-instructions">
      <StagesNav :current="'practitioner'" />
      <h2>First, choose your practitioner...</h2>
      <p>We have <strong>2 doctors</strong> available who are licensed and certified to work with patients in your state. Please select the doctor you prefer.</p>
    </div>
    <div class="signup-container signup-stage-container">
      <div class="signup-practitioner-wrapper cf">
        <div :class="{ 'practitioner-wrapper': true, active: dr.id === store.signup.data.practitioner_id }" v-for="dr in practitioners" tabindex="0" @click="select(dr)">
          <div class="practitioner-bg" :style="{ backgroundImage: 'url(' + dr.info.background_picture_url + ')' }"></div>
          <img class="practitioner-avatar" :src="dr.info.picture_url" />
          <h3 class="practitioner-name text-centered">Dr. {{ dr.name }}, {{ dr.info.type_name }}</h3>
          <p class="practitioner-license text-centered">License {{ dr.info.license_number }} {{ dr.info.license_state }}</p>
          <div class="practitioner-info-wrapper">
            <p>{{ dr.info.description }}</p>
            <hr class="practitioner-divider" />
            <ul class="practitioner-info">
              <li><span>Graduated:</span> {{ dr.info.graduated_at.date | year }}</li>
              <li><span>Degree:</span> {{ dr.info.school }}</li>
              <li><span>Specialties:</span> {{ dr.info.specialty | specialty }}</li>
            </ul>
            <hr class="practitioner-divider" />
            <p class="practitioner-rate text-centered"><span>$150</span>/hour</p>
          </div>
        </div>
      </div>
      <p class="error-text" v-html="errorText" v-show="errorText"></p>
      <div class="text-centered">
        <button class="button button--blue" style="width: 160px" :disabled="processing" @click="getAvailability(store.signup.data.practitioner_id)">
          <span v-if="!processing">Continue</span>
          <LoadingBubbles v-else-if="processing" :style="{ width: '16px', fill: 'white' }" />
          <i v-else-if="isComplete" class="fa fa-check"></i>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import LoadingBubbles from '../../../commons/LoadingBubbles.vue';
import StagesNav from '../util/StagesNav.vue';
import moment from 'moment';
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
      processing: false,
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
    }
  },
  methods: {
    getAvailability(id) {
      this.processing = true;
      if (!this.store.signup.data.practitioner_id) {
        this.errorText = 'Please select a practitioner by clicking their box.';
        this.processing = false;
        return;
      }
      axios.get(`/api/v1/practitioners/${id}?include=availability`).then(response => {
        this.store.signup.availability = transformAvailability(response.data.meta.availability, Laravel.user.userType);
        if (!this.store.signup.availability.length) {
          this.errorText = 'Unfortunately we don\'t have any availability for that practitioner in the next 4 weeks. Please call us at <a href="tel:8006909989">800-690-9989</a> to book an appointment.';
          this.processing = false;
        } else {
          this.$router.push({ name: 'phone', path: '/phone' });
        }
      });
    },
    select(dr) {
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
