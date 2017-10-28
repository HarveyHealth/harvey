<template>
  <PageContainer>
    <PageHeader :heading="heading" />
    <Grid :flexAt="'l'" :columns="[{ l:'1of3' }, { l:'2of3' }]" :gutters="{ s:3 }">
      <h3 slot="1" class="bg-blue ma0 p4">First</h3>
      <Grid slot="2" :columns="[{ ns:'2of3' }, { ns:'1of3' }]" :gutters="{ s:3 }">
        <h4 slot="1" class="bg-red pa2 ma0">Inner</h4>
        <h4 slot="2" class="bg-yellow pa2 ma0">Second</h4>
      </Grid>
    </Grid>
  </PageContainer>
</template>

<script>
import { Grid, PageHeader, PageContainer } from 'layout';

export default {
  name: 'dashboard',
  components: {
    Grid,
    PageHeader,
    PageContainer
  },
  data() {
    return {
      heading: `${App.Config.user.isAdmin ? 'Admin ' : ''}Dashboard`
    }
  },
  mounted() {
    App.setState('misc.currentPage', 'dashboard');
  }
}
</script>
