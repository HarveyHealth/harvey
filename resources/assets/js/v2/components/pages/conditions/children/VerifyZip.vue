<template>
  <div>
    <SlideIn>
      <p>Zip codes are important for our business.</p>
      <MultiInput
        :quantity="5"
        :focus-next="{refs: $refs, ref: 'submit'}"
        :get-value="(zip) => setState('conditions.zip', zip)"
      />
      <ButtonInput :text="'Verify'" :ref="'submit'" />
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
