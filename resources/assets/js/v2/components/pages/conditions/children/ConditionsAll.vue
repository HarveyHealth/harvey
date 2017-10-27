<template>
  <Row>
    <Column v-for="(condition, i) in State('conditions.all')" :config="{ xs: '1of2', md: '1of3', lg: '1of5' }" :key="i">
      <a :href="'/conditions/' + condition.slug" class="db tc pa2">
        <div class="ConditionOption">
          <SvgIcon :id="condition.image_url" :width="'60px'" :height="'60px'" />
        </div>
        <Space isBottom :size="3" />
        <Paragraph isLight>{{ condition.name }}</Paragraph>
      </a>
      <Space isBottom :size="4" />
    </Column>
  </Row>
</template>

<script>
import components from 'components';
export default { components };
</script>
