<template>
  <div>
    <div class="center db tc mw6">
      <SvgIcon :id="State('conditions.condition.image_url')" :width="'80px'" :height="'160px'" />
    </div>
    <SlideIn class="center mw7 tc">
      <Heading1 isBold isLight doesExpand>{{ State('conditions.condition.name') }}</Heading1>
      <Spacer isBottom :size="3" />
      <Paragraph isLight>{{ State('conditions.condition.description') }}</Paragraph>
      <Spacer isBottom :size="4" />
      <InputButton
        :text="button"
        :type="'whiteFilled'"
        :on-click="() => setState('conditions.prefaceRead', true)"
        :width="'200px'" />
    </SlideIn>
  </div>
</template>

<script>
import { InputButton } from 'inputs';
import { SlideIn, Spacer } from 'layout';
import { SvgIcon } from 'icons';
import { Paragraph, Heading1 } from 'typography';

export default {
  components: { InputButton, SlideIn, Spacer, SvgIcon, Paragraph, Heading1 },
  data() {
    return {
      button: '<span style="font-size:20px; padding-right:16px;">Continue</span><i class="fa fa-chevron-right" style="font-size: 14px"></i>'
    }
  }
};
</script>
