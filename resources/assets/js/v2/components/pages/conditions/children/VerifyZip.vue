<template>
  <div class="center tc mw6">
    <SvgIcon :id="'map'" :width="'120px'" :height="'120px'" />
    <Space isBottom :size="4" />
    <SlideIn v-if="!State('conditions.zipValidation')">
      <Heading1 isLight>What is your zip code?</Heading1>
      <Space isBottom :size="4" />
      <Paragraph isLight>Harvey does not have licensed doctors in every state. Please enter your zip code to verify that we can work together.</Paragraph>
      <Space isBottom :size="4" />
      <MultiInput
        :auto-focus="true"
        :quantity="5"
        :color="'light'"
        :focus-next="{ refs: $refs, ref: 'submit' }"
        :get-value="zip => setState('conditions.zip', zip)" />
      <Space isBottom :size="4" />
      <ButtonInput
        :is-disabled="State('conditions.zip').length < 5"
        :is-done="State('wasRequested.zip') && !State('isLoading.zip')"
        :is-processing="State('isLoading.zip')"
        :on-click="() => Http.zip.get(State('conditions.zip'), Http.zip.getResponse)"
        :text="'Verify'"
        :type="'whiteFilled'"
        :ref="'submit'"
        :width="'140px'" />
    </SlideIn>
    <div v-if="State('conditions.zipValidation.is_serviceable') === false">
      <SlideIn>
        <Heading1 isLight>Unfortunately, we cannot service patients in your state yet.</Heading1>
        <Space isBottom :size="4" />
        <Paragraph isLight>We will let you know as soon as we launch in your state. In the meantime, you can follow on us social media for free health tips from our team of Naturopathic Doctors.</Paragraph>
        <Space isBottom :size="4" />
        <a href="#" class="white" @click="reEnterZip">
          <i class="fa fa-undo margin-right-xs"></i> Try Again
        </a>
        <div>
          <a class="social-icon white" v-for="icon in Config.misc.socialMedia" :href="icon.href" target="_blank">
            <i :class="icon.class"></i>
          </a>
        </div>
      </SlideIn>
    </div>
  </div>
</template>

<script>
import components from 'components';

export default {
  components,
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
