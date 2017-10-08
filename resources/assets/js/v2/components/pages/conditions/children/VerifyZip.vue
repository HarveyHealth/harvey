<template>
  <div>
    <SlideIn>
      <p>Zip codes are important for our business.</p>
      <MultiInput
        :quantity="5"
        :focus-next="{ refs: $refs, ref: 'submit' }"
        :get-value="zip => setState('conditions.zip', zip)" />
      <ButtonInput
        :is-done="State('wasRequested.zip') && !State('isLoading.zip')"
        :is-processing="State('isLoading.zip')"
        :on-click="() => Http.zip.get(State('conditions.zip'), Http.zip.getResponse)"
        :text="'Verify'"
        :ref="'submit'"
        :width="'120px'" />
    </SlideIn>
  </div>
</template>

<script>
import { Inputs, Util } from '../../../base';

export default {
  components: {
    ButtonInput: Inputs.ButtonInput,
    MultiInput: Inputs.MultiInput,
    SlideIn: Util.SlideIn
  }
}
</script>
