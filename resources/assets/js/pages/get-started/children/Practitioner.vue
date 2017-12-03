<template>
    <SlideIn v-if="!$root.$data.signup.completedSignup" class="ph3 pv4">
        <div class="tc">

            <div v-if="$root.$data.global.loadingPractitioners">
                <LoadingSpinner :color="'light'" />
                <Spacer isBottom :size="3" />
                <Heading1 :color="'light'">Loading Practitioners...</Heading1>
            </div>

            <div v-else-if="hasNoPractitioners" class="m0auto mw6">
                <Icon :fill="'white'" :icon="'globe'" :height="'100px'" :weight="'100px'" />
                <Spacer isBottom :size="3" />
                <Heading1 doesExpand :color="'light'">We&rsquo;re sorry!</Heading1>
                <Spacer isBottom :size="3" />
                <Paragraph :color="'light'">
                    Unfortunately, we no longer have practitioner availability in your area. Please give us a call at <a href="tel:8006909989">800-690-9989</a>, or talk with a representative by clicking the chat button at the bottom right corner of the page.
                </Paragraph>
                <Spacer isBottom :size="3" />
                <SocialIcons />
                <Spacer isBottom :size="3" />
                <a href="/logout"><InputButton :text="'Log Out'" /></a>
            </div>

            <template v-else>
                <div class="signup-stage-instructions color-white">
                    <StagesNav :current="'practitioner'" />
                    <h2 class="heading-1 font-normal color-white">Choose Your Doctor</h2>
                    <p>Doctor availability is based on state licensing and regulation. Please select the doctor that best suits your health needs.</p>
                </div>
                <div class="signup-container large">
                    <div class="signup-practitioner-wrapper cf">
                        <div class="practitioner-wrapper">
                            <h3 class="signup-section-header heading-2 font-centered">Available Doctors</h3>
                            <div class="signup-practitioner-selector-wrap" v-for="(dr, index) in practitioners">
                                <button :class="{ 'signup-practitioner-selector': true, 'active': index === selected }" @click="select(dr, index)">
                                    <img :src="determineImage(dr.info.picture_url, 'user')" />
                                </button>
                                <small class="signup-practitioner-selector-name">{{ dr.name }}</small>
                            </div>
                        </div>
                        <div class="practitioner-wrapper right">
                            <div v-if="hasSelection">
                                <div class="practitioner-bg" :style="{ backgroundImage: 'url(' + determineImage(practitioners[selected].info.background_picture_url, 'background') + ')' }"></div>
                                <img class="practitioner-avatar" :src="determineImage(practitioners[selected].info.picture_url, 'user')" />
                                <h3 v-if="practitioners[selected].name" class="practitioner-name font-centered font-normal heading-3">{{ practitioners[selected].name }}, ND</h3>
                                <p v-if="practitioners[selected].info.license_number" class="practitioner-license text-centered">License {{ practitioners[selected].info.license_number }}</p>
                                <div class="practitioner-info-wrapper font-sm">
                                    <p v-if="practitioners[selected].info.description">{{ practitioners[selected].info.description }}</p>
                                    <hr class="practitioner-divider" />
                                    <ul class="practitioner-info">
                                        <li v-if="practitioners[selected].info.graduated_at">
                                            <span>Graduated:</span> {{ practitioners[selected].info.graduated_at.date | year }}
                                        </li>
                                        <li v-if="practitioners[selected].info.school">
                                            <span class="font-sm">Degree:</span> {{ practitioners[selected].info.school }}
                                        </li>
                                        <li v-if="practitioners[selected].info.specialty">
                                            <span>Specialties:</span> {{ practitioners[selected].info.specialty | specialty }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="closing-selection" v-if="hasSelection">
                        Your selection is <span class="font-bold">{{ practitioners[selected].name }}, ND.</span>
                    </p>
                    <p class="copy-error tc" v-html="errorText" v-show="errorText"></p>
                    <div class="font-centered" ref="button">
                        <button class="button button--blue" style="width: 160px" :disabled="isProcessing" @click="getAvailability(store.signup.data.practitioner_id)">
                            <span v-if="!isProcessing">Continue</span>
                            <LoadingSpinner v-else-if="isProcessing" />
                            <i v-else-if="isComplete" class="fa fa-check"></i>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </SlideIn>
</template>

<script>
import moment from 'moment';
import { LoadingSpinner } from 'feedback';
import { Icon, SocialIcons } from 'icons';
import { InputButton } from 'inputs';
import { Card, CardContent, SlideIn, Spacer } from 'layout';
import { Heading1, Paragraph } from 'typography';

import StagesNav from '../util/StagesNav.vue';
import transformAvailability from '../../../utils/methods/transformAvailability';

export default {
  name: 'practitioner',
  components: {
      Card,
      CardContent,
      Heading1,
      Icon,
      InputButton,
      LoadingSpinner,
      Paragraph,
      SlideIn,
      SocialIcons,
      Spacer,
    StagesNav
  },
  data() {
    return {
      containerClasses: {
        'anim-fade-slideup': true,
        'anim-fade-slideup-in': false,
        'container': true,
        'pad-md': true,
        'flex-wrapper': true,
        'height-100': true,
        'justify-center': true
      },
      errorText: null,
      isProcessing: false,
      store: this.$root.$data,
    };
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
      if (this.hasNoPractitioners) {
        window.onbeforeunload = null;
      }
    }
  },
  computed: {
    hasNoPractitioners() {
      return !this.$root.$data.global.loadingPractitioners && !this.practitioners.length;
    },
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
    determineImage(image, type) {
      return image ? image : `https://d35oe889gdmcln.cloudfront.net/assets/images/default_${type}_image.png`;
    },
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
          this.errorText = 'Unfortunately, we don\'t have any availability for that doctor in the next month, please choose another doctor. If you\'re stuck, give us a call at <a class="font-sm" href="tel:8006909989">800-690-9989</a>.';
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

    if(App.Logic.misc.shouldTrack()) {
      analytics.page('Practitioner');
    }
  }
};
</script>
