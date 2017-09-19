<template>
  <div>
    <img :src="State('conditions.all')[0].image_url" style="width:80px; height:80px" /><br>
    <SlideIn>
      <h3 class="heading-3-expand">{{ State('conditions.all')[0].name }}</h3>
      <p>{{ State('conditions.all')[0].description }}</p>
      <button class="button" @click="setState('conditions.prefaceRead', true)">Continue</button>
    </SlideIn>
  </div>
</template>

<script>
import { Util } from '../../../base';

export default {
  components: {
    SlideIn: Util.SlideIn
  },
}
</script>
