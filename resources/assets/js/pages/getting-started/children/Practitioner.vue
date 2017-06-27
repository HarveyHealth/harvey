<template>
  <div :class="containerClasses">
    <div class="signup-stage-instructions">
      <div>Dots</div>
      <h2>First, choose your practitioner...</h2>
      <p>We have <strong>2 doctors</strong> available who are licensed and certified to work with patients in your state. Please select the doctor you prefer.</p>
    </div>
    <div class="signup-container signup-stage-container">
      <div class="signup-practitioner-wrapper cf">
        <div class="practitioner-wrapper" v-for="dr in practitioners" tabindex="-1">
          <div class="practitioner-bg" :style="{ backgroundImage: 'url(' + dr.bg + ')' }"></div>
          <img class="practitioner-avatar" :src="dr.avatar" />
          <h3 class="practitioner-name text-centered">Dr. {{ dr.name }}, {{ dr.type }}</h3>
          <p class="practitioner-license text-centered">License {{ dr.license }} {{ dr.state }}</p>
          <div class="practitioner-info-wrapper">
            <p>{{ dr.description }}</p>
            <hr class="practitioner-divider" />
            <ul class="practitioner-info">
              <li><span>Graduated:</span> {{ dr.graduated }}</li>
              <li><span>Degree:</span> {{ dr.degree }}</li>
              <li><span>Specialties:</span> {{ dr.specialties }}</li>
            </ul>
            <hr class="practitioner-divider" />
            <p class="practitioner-rate text-centered"><span>${{ dr.rate }}</span>/hour</p>
          </div>
        </div>
      </div>
      <p class="error-text">Error text</p>
      <div class="text-centered">
        <button class="button button--blue" style="width: 160px" :disabled="processing">
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

export default {
  name: 'practitioner',
  components: {
    LoadingBubbles,
  },
  data() {
    return {
      containerClasses: {
        'anim-fade-slideup': true,
        'anim-fade-slideup-in': false,
        'container': true,
      },
      // Dummy data until the API updates are merged into current-release
      practitioners: [
        {
          avatar: 'https://unsplash.it/90/90',
          bg: 'https://unsplash.it/400/100',
          degree: 'Northeast College of Naturopathic Medicine',
          description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce aliquam et arcu posuere ornare. Vestibulum vitae sapien risus. Donec tempus congue augue quis facilisis. Nunc ac hendrerit quam. Nulla viverra porttitor dui sit amet sagittis. In vel felis at sapien aliquet interdum. Pellentesque rutrum velit purus, ut tempor purus iaculis vel.',
          graduated: '2005',
          license: 'ND-382',
          name: 'Stu Waters',
          rate: '320',
          specialties: 'diabetes, COPD, migraines, gut, Lupus',
          state: 'NJ',
          type: 'N.D.',
        },
        {
          avatar: 'https://unsplash.it/80/80',
          bg: 'https://unsplash.it/500/150',
          degree: 'Southwest College of Naturopathic Medicine',
          description: 'Praesent elit turpis, sagittis quis congue id, tincidunt at magna. Nullam volutpat nisl risus. In a justo quis dui tincidunt cursus. Etiam ut rhoncus nunc. Donec eu arcu vulputate, placerat mi eget, vehicula risus. Maecenas elementum hendrerit risus nec ultrices. Fusce pulvinar pellentesque porttitor. Integer at molestie nibh.',
          graduated: '2009',
          license: 'ND-432',
          name: 'Patricia Powers',
          rate: '180',
          specialties: 'craving management, sleep deprivation, pecans',
          state: 'CA',
          type: 'N.D.',
        },
      ],
      processing: false,
    }
  },
  mounted () {
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', true, 300);
  },
  beforeDestroy() {
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', false);
  }
}
</script>
