<template>
  <div class="center tc mw6">
    <SvgIcon :id="'map'" :width="'120px'" :height="'120px'" />
    <Spacer isBottom :size="4" />
    <SlideIn v-if="!State('conditions.zipValidation')">
      <Heading1 isLight>What is your zip code?</Heading1>
      <Spacer isBottom :size="4" />
      <Paragraph isLight>Harvey does not have licensed doctors in every state. Please enter your zip code to verify that we can work together.</Paragraph>
      <Spacer isBottom :size="4" />
      <MultiInput
        :color="'light'"
        :focus-next="{ refs: $refs, ref: 'submit' }"
        :get-value="zip => setState('conditions.zip', zip)"
        :is-auto-focused="true"
        :quantity="5"
        :validation="/\d/" />
      <Spacer isBottom :size="4" />
      <InputButton
        :is-disabled="State('conditions.zip').length < 5"
        :is-done="State('wasRequested.zip') && !State('isLoadingSpinner.zip')"
        :is-processing="State('isLoadingSpinner.zip')"
        :on-click="() => Http.zip.get(State('conditions.zip'), Http.zip.getResponse)"
        :text="'Verify'"
        :type="'whiteFilled'"
        :ref="'submit'"
        :width="'140px'" />
    </SlideIn>
    <div v-if="State('conditions.zipValidation.is_serviceable') === false">
      <SlideIn>
        <Heading1 isLight doesExpand>Unfortunately, we cannot service patients in your state yet.</Heading1>
        <Spacer isBottom :size="4" />
        <Paragraph isLight>We will let you know as soon as we launch in your state. In the meantime, you can follow on us social media for free health tips from our team of Naturopathic Doctors.</Paragraph>
        <Spacer isBottom :size="4" />
        <a href="#" class="white" @click="reEnterZip">
          <i class="fa fa-undo"></i><Spacer isRight :size="3" />Try Again
        </a>
        <Spacer isBottom :size="4" />
        <div>
          <a class="social-icon f2 white" v-for="icon in Config.misc.socialMedia" :href="icon.href" target="_blank">
            <i :class="icon.class"></i>
          </a>
        </div>
      </SlideIn>
    </div>
  </div>
</template>

<script>
import { InputButton, MultiInput } from 'inputs';
import { Heading1, Paragraph } from 'typography';
import { SlideIn, Spacer } from 'layout';
import { SvgIcon } from 'icons';

export default {
  components: { InputButton, Heading1, MultiInput, Paragraph, SlideIn, Spacer, SvgIcon },
  methods: {
    reEnterZip() {
      App.setState('wasRequested.zip', false);
      App.setState('conditions.zipValidation', null);
    }
  }
};
</script>

<style lang="scss" scoped>
  .social-icon {
    padding: 4%;
  }
</style>
