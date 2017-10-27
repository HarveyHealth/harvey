<template>
  <div>
    <div class="center db tc mw6">
      <SvgIcon :id="State('conditions.condition.image_url')" :width="'80px'" :height="'160px'" />
    </div>
    <SlideIn class="center mw7 tc">
      <Heading1 isBold isLight doesExpand>{{ State('conditions.condition.name') }}</Heading1>
      <Space isBottom :size="3" />
      <Paragraph isLight>{{ State('conditions.condition.description') }}</Paragraph>
      <Space isBottom :size="4" />
      <ButtonInput
        :text="button"
        :type="'whiteFilled'"
        :on-click="() => setState('conditions.prefaceRead', true)"
        :width="'200px'" />
    </SlideIn>
  </div>
</template>

<script>
import components from 'components';

export default {
  components,
  data() {
    return {
      button: '<span style="font-size:20px; padding-right:16px;">Continue</span><i class="fa fa-chevron-right" style="font-size: 14px"></i>'
    }
  }
};
</script>
