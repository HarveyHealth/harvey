<template>
  <div>
    <div class="block margin-0a max-width-md">
      <SvgIcon :id="State('conditions.condition.image_url')" />
    </div>
    <SlideIn>
      <h3 class="heading-3-expand">{{ State('conditions.condition.name') }}</h3>
      <p>{{ State('conditions.condition.description') }}</p>
      <ButtonInput :text="'Continue'" :on-click="() => setState('conditions.prefaceRead', true)" />
    </SlideIn>
  </div>
</template>

<script>
import { Inputs, Util } from '../../../base';

export default {
  components: {
    ButtonInput: Inputs.ButtonInput,
    SlideIn: Util.SlideIn,
    SvgIcon: Util.SvgIcon
  },
}
</script>
