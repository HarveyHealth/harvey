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
                <div class="m0auto mw6">
                    <Heading1 doesExpand :color="'light'">Choose Your Doctor</Heading1>
                    <Spacer isBottom :size="2" />
                    <Paragraph :color="'light'">Doctor availability is based on state licensing and regulation. Please select the doctor that best suits your health needs.</Paragraph>
                </div>
                <Spacer isBottom :size="4" />
                <Card class="m0auto mw7">
                    <CardContent>
                        <Grid :columns="[{m:'1of2'},{m:'1of2'}]" :gutters="{s:3}">
                            <div :slot="1" class="col pa3">
                                <Heading3 :color="'muted'" class="tc uppercase">Available Doctors</Heading3>
                                <Spacer isBottom :size="3" />
                                <Grid :flexAt="'ns'" :columns="practitioners.map(dr => ({s:'1of2', ns:'1of4', m:'1of2'}))" :gutters="{s:2}">
                                    <AvatarSelection
                                        v-for="(dr, index) in practitioners"
                                        :slot="index + 1"
                                        :key="index"
                                        :caption="dr.name"
                                        :image="determineImage(dr.info.picture_url, 'user')"
                                        :isActive="index === selected"
                                        :onClick="() => select(dr, index)"
                                    />
                                </Grid>
                            </div>
                            <div :slot="2" class="col">
                                <PractitionerInfo :practitioner="practitioners[selected]" />
                            </div>
                        </Grid>
                        <Paragraph v-if="hasSelection">
                            Your selection is <span class="bg-gray-0 font-bold">{{ practitioners[selected].name }}, ND.</span>
                        </Paragraph>
                        <Spacer isBottom :size="3" v-show="errorText" />
                        <div v-show="errorText">
                            <Paragraph class="red tc" v-html="errorText"></Paragraph>
                        </div>
                        <Spacer isBottom :size="3" />
                        <div ref="button">
                            <InputButton
                                :isProcessing="isProcessing"
                                :onClick="() => getAvailability(store.signup.data.practitioner_id)"
                                :text="'Continue'"
                                :width="'160px'"
                            />
                        </div>
                    </CardContent>
                </Card>
            </template>
        </div>
    </SlideIn>
</template>

<script>
import moment from 'moment';
import { LoadingSpinner } from 'feedback';
import { Icon, SocialIcons } from 'icons';
import { AvatarSelection, InputButton } from 'inputs';
import { Card, CardContent, Grid, SlideIn, Spacer } from 'layout';
import { Heading1, Heading3, Paragraph } from 'typography';

import PractitionerInfo from './PractitionerInfo.vue';
import transformAvailability from '../../../../utils/methods/transformAvailability';

export default {
  name: 'practitioner',
  components: {
      AvatarSelection,
      Card,
      CardContent,
      Grid,
      Heading1,
      Heading3,
      Icon,
      InputButton,
      LoadingSpinner,
      Paragraph,
      PractitionerInfo,
      SlideIn,
      SocialIcons,
      Spacer
  },
  data() {
    return {
      errorText: null,
      isProcessing: false,
      store: this.$root.$data,
    };
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

<style lang="scss" scoped>
    @import '~sass';

    .col {
        background-color: $color-gray-0;
        position: relative;
    }
</style>
