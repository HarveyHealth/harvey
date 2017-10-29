<template>
  <PageContainer>
    <PageHeader :heading="Config.user.isAdmin ? 'Admin Dashboard' : 'Dashboard'" />
    <div class="mw8 pa2 pa3-m">
      <Loading v-if="!isDoneLoading" class="mt3" />
      <SlideIn v-else>
        <Grid :flexAt="'l'" :columns="topRowColumnConfig" :gutters="{ s:2, m:3 }">
          <Card :slot="1" :heading="'Appointments'">
            <AppointmentCardInfo v-for="(appt, i) in State('appointments.data.upcoming')"
              :appointment="appt"
              :hasBorder="i < State('appointments.data.upcoming').length - 1"
              :key="appt.id" />
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
import AppointmentCardInfo from './AppointmentCardInfo.vue';
import AvatarCardHeading from './AvatarCardHeading.vue';

export default {
  name: 'dashboard',
  components: { AppointmentCardInfo, AvatarCardHeading, Card, CardContent, Grid, Loading, PageHeader, PageContainer, Paragraph, SlideIn, Space },
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

    if (!this.State('appointments.wasRequested.upcoming')) {
      App.Http.appointments.getUpcoming(App.Http.appointments.getUpcomingResponse);
    }

    if (!this.State('practitioners.wasRequested')) {
      App.Http.practitioners.get(App.Http.practitioners.getResponse);
    }
  }
}
</script>
