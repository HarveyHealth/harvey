<template>
  <div>
    <img :src="State('conditions.all')[0].image_url" style="width:80px; height:80px" /><br>
    <SlideIn>
      <h3 class="heading-3-expand">{{ State('conditions.all')[0].name }}</h3>
      <p>{{ State('conditions.all')[0].description }}</p>
      <ButtonInput :text="'Continue'" :on-click="() => setState('conditions.prefaceRead', true)" />
    </SlideIn>
  </div>
</template>

<script>
import { Inputs, Util } from '../../../base';

export default {
  components: {
    ButtonInput: Inputs.ButtonInput,
    SlideIn: Util.SlideIn
  },
}
</script>
