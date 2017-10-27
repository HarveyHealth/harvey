<template>
  <Row>
    <Column v-for="(condition, i) in State('conditions.all')" :config="{ xs: '1of2', md: '1of3', lg: '1of5' }" :key="i">
      <a :href="'/conditions/' + condition.slug" class="block font-centered">
        <div class="ConditionOption">
          <SvgIcon :id="condition.image_url" :width="'60px'" :height="'60px'" />
        </div>
        <p class="color-white is-padding is-paddingless-bottom">{{ condition.name }}</p>
      </a>
    </Column>
  </Row>
</template>

<script>
import components from 'components';
export default { components };
</script>
