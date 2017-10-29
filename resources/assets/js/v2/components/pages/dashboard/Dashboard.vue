<template>
  <PageContainer>
    <PageHeader :heading="heading" />
    <div class="mw8 pa2 pa3-m">
      <Grid :flexAt="'m'" :columns="topRowColumns" :gutters="{ s:2, m:3 }">
        <Card :slot="1" :heading="'Appointments'">
          <CardContent>Oh, boy</CardContent>
        </Card>
        <Card :slot="2" :heading="'Practitioner'" v-if="Config.user.isPatient">
          <CardContent>Oh, boy</CardContent>
        </Card>
      </Grid>
    </div>
  </PageContainer>
</template>

<script>
import { Card, CardContent, Grid, PageHeader, PageContainer } from 'layout';

export default {
  name: 'dashboard',
  components: {
    Card,
    CardContent,
    Grid,
    PageHeader,
    PageContainer
  },
  data() {
    return {
      heading: `${App.Config.user.isAdmin ? 'Admin ' : ''}Dashboard`,
      topRowColumns: App.Config.user.isPatient ? [{ m:'1of2' }, { m:'1of2' }] : [{ m:'1of1' }]
    }
  },
  mounted() {
    App.setState('misc.currentPage', 'dashboard');
  }
}
</script>
