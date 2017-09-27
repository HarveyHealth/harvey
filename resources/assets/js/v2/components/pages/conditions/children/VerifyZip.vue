<template>
  <div>
    <SlideIn class="font-centered margin-0a max-width-md space-children-lg">
      <SvgIcon :id="'map'" :width="'120px'" :height="'120px'" />
      <p class="heading-1">What is your zip code?</p>
      <p>Harvey does not have licensed doctors in every state. Please enter your zip code below to verify we can work together.</p>
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
  }
}
</script>
