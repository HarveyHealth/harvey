<template>
  <Row :gutter="'md'">
    <Column v-for="(condition, i) in State('conditions.all')" :config="{ xs: '1of2', sm: '1of3', md: '1of5' }" :key="i">
      <a :href="'/conditions/' + condition.slug" class="block font-centered">
        <div class="margin-0a max-width-icon">
          <SvgIcon :id="condition.image_url" />
        </div>
        <span>{{ condition.name }}</span>
      </a>
    </Column>
  </Row>
</template>

<script>
import { Layout, Util } from '../../../base';

export default {
  components: {
    Column: Layout.Column,
    Row: Layout.Row,
    SvgIcon: Util.SvgIcon
  }
}
</script>
