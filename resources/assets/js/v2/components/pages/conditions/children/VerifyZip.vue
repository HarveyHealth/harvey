<template>
  <div class="font-centered margin-0a max-width-md space-children-lg">
    <SvgIcon :id="'map'" :width="'120px'" :height="'120px'" />
    <SlideIn class="space-children-lg" v-if="!State('conditions.zipValidation')">
      <p class="heading-1">What is your zip code?</p>
      <p class="font-lg">Harvey does not have licensed doctors in every state. Please enter your zip code to verify that we can work together.</p>
      <MultiInput
        :auto-focus="true"
        :quantity="5"
        :color="'light'"
        :focus-next="{ refs: $refs, ref: 'submit' }"
        :get-value="zip => setState('conditions.zip', zip)" />
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
      <SlideIn class="space-children-lg">
        <p class="heading-1">Unfortunately, we cannot service patients in your state yet.</p>
        <p class="font-lg">We will let you know as soon as we launch in your state. In the meantime, you can follow on us social media for free health tips from our team of Naturopathic Doctors.</p>
        <a href="#" class="font-md color-white" @click="reEnterZip">
          <i class="fa fa-undo margin-right-xs"></i> Try Again
        </a>
        <div class="is-paddingless">
          <a class="color-white inline font-xxl" style="padding: 4%" v-for="icon in Config.misc.socialMedia" :href="icon.href" target="_blank">
            <i :class="icon.class"></i>
          </a>
        </div>
      </SlideIn>
    </div>
  </div>
</template>

<script>
import { Inputs, Util } from '../../../base';

export default {
  components: {
    ButtonInput: Inputs.ButtonInput,
    MultiInput: Inputs.MultiInput,
    SlideIn: Util.SlideIn,
    SvgIcon: Util.SvgIcon
  },
  methods: {
    reEnterZip() {
      App.setState('wasRequested.zip', false);
      App.setState('conditions.zipValidation', null);
    }
  }
}
</script>
