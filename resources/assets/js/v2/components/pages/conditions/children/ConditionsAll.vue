<template>
  <Row :gutter="'md'">
    <Column v-for="(condition, i) in State('conditions.all')" :config="{ xs: '1of2', sm: '1of3', md: '1of5' }" :key="i">
      <a :href="'/conditions/' + condition.slug" class="block font-centered">
        <img :src="condition.image_url" style="width:80px; height:80px" /><br>
        <span>{{ condition.name }}</span>
      </a>
    </Column>
  </Row>
</template>

<script>
import { Layout } from '../../../base';

export default {
  components: {
    Column: Layout.Column,
    Row: Layout.Row
  }
}
</script>
