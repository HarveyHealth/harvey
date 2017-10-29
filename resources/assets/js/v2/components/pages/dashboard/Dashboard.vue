<template>
  <PageContainer>
    <PageHeader :heading="heading" />
    <div class="mw8 pa2 pa3-m">
      <Loading v-if="!isDoneLoading" class="mt3" />
      <SlideIn v-else>
        <Grid :flexAt="'l'" :columns="topRowColumnConfig" :gutters="{ s:2, m:3 }">
          <Card :slot="1" :heading="'Appointments'">
            <CardContent>

            </CardContent>
          </Card>
          <Card :slot="2" :heading="'Practitioner'" v-if="shouldShowDoctorInfo">
            <AvatarCardHeading :heading="State('practitioners.userDoctor.attributes.name')" />
            <CardContent>
              <Paragraph>{{ State('practitioners.userDoctor.attributes.description') }}</Paragraph>
            </CardContent>
          </Card>
        </Grid>
      </SlideIn>
    </div>
  </PageContainer>
</template>

<script>
import { Loading } from 'feedback';
import { Paragraph } from 'typography';
import { Card, CardContent, Grid, PageHeader, PageContainer, SlideIn, Space } from 'layout';
import AvatarCardHeading from './AvatarCardHeading.vue';

export default {
  name: 'dashboard',
  components: { AvatarCardHeading, Card, CardContent, Grid, Loading, PageHeader, PageContainer, Paragraph, SlideIn, Space },
  data() {
    return {
      heading: `${App.Config.user.isAdmin ? 'Admin ' : ''}Dashboard`
    }
  },
  computed: {
    isDoneLoading() {
      return this.State('practitioners.data.all').length;
    },
    shouldShowDoctorInfo() {
      return App.Config.user.isPatient && this.State('practitioners.userDoctor');
    },
    topRowColumnConfig() {
      return this.shouldShowDoctorInfo ? [{ l:'1of2' }, { l:'1of2' }] : [{ l:'1of1' }];
    }
  },
  mounted() {
    App.setState('misc.currentPage', 'dashboard');

    if (!this.State('practitioners.wasRequested')) {
      App.Http.practitioners.get(App.Http.practitioners.getResponse);
    }
  }
}
</script>
