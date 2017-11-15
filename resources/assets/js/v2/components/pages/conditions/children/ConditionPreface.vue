<template>
  <div class="space-children-sm">
    <div class="block font-centered margin-0a max-width-md margin-top-md">
      <img :src="State('conditions.condition.image_url')" class="max-width-xs">
    </div>
    <SlideIn class="space-children-xl">
      <button class="Button Button--condition-nav is-left">
        <a href="/#conditions" class="color-white font-normal">
          <i class="fa fa-chevron-left"></i> New Condition
        </a>
      </button>
      <h3 class="color-white heading-1 font-bold font-centered">{{ State('conditions.condition.name') }}</h3>
      <p class="font-centered font-lg">{{ State('conditions.condition.description') }}</p>
      <div id="wordpress-feed"></div>
      <div class="font-centered">
        <ButtonInput
          :text="button"
          :type="'whiteFilled'"
          :on-click="() => setState('conditions.prefaceRead', true)"
          :width="'200px'" />
      </div>
    </SlideIn>
  </div>
</template>

<script>
import { Inputs, Util } from '../../../base';

export default {
  data() {
    return {
      button: '<span style="font-size:20px; padding-right:16px;">Start Quiz</span><i class="fa fa-chevron-right" style="font-size: 14px"></i>'
    };
  },
  components: {
    ButtonInput: Inputs.ButtonInput,
    SlideIn: Util.SlideIn,
    SvgIcon: Util.SvgIcon
  }
};
</script>
