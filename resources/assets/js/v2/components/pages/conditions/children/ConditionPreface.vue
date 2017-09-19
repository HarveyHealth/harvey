<template>
  <div>
    <MultiInput :quantity="3" :focus-next="{ refs: $refs, ref: 'button' }" :get-value="(v) => setState('misc.foo', v)" />
    <img :src="State('conditions.all')[0].image_url" style="width:80px; height:80px" /><br>
    <SlideIn>
      <h3 class="heading-3-expand">{{ State('conditions.all')[0].name }}</h3>
      <p>{{ State('conditions.all')[0].description }}</p>
      <button class="button" @click="setState('conditions.prefaceRead', true)" :ref="'button'">Continue</button>
    </SlideIn>
  </div>
</template>

<script>
import { Inputs, Util } from '../../../base';

export default {
  components: {
    MultiInput: Inputs.MultiInput,
    SlideIn: Util.SlideIn
  },
}
</script>
