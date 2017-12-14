<template>
  <PageContainer>
    <PageHeader :heading="Config.user.isAdmin ? 'Admin Dashboard' : 'Dashboard'" />
    <div class="mw8 pa2 pa3-m">
      <LoadingSpinner v-if="!isDoneLoading" class="mt3" />
      <SlideIn v-else>
        <Grid :flexAt="'l'" :columns="topRowColumnConfig" :gutters="{ s:2, m:3 }">
          <Card :slot="1" :heading="'Appointments'">
            <div v-if="upcomingAppointments.length">
              <AppointmentCardInfo
                v-for="(appt, i) in upcomingAppointments"
                :appointment="appt"
                :hasBorder="i < upcomingAppointments.length - 1"
                :key="appt.id"
              />
            </div>
            <CardContent v-else>
              <Paragraph :weight="'thin'">No upcoming appointments scheduled</Paragraph>
            </CardContent>
          </Card>
          <Card :slot="2" :heading="'Practitioner'" v-if="shouldShowDoctorInfo">
            <AvatarCardHeading :heading="State('practitioners.userDoctor.attributes.name')" />
            <CardContent>
              <Paragraph :weight="'thin'">{{ State('practitioners.userDoctor.attributes.description') }}</Paragraph>
            </CardContent>
          </Card>
        </Grid>
        <Grid :flexAt="'l'" :columns="[{ l:'1of2' }, { l:'1of2' }]" :gutters="{ s:2, m:3 }">
          <Card :slot="1" :heading="'Contact Info'">
            <AvatarCardHeading :heading="Util.misc.fullName(Config.user.info)" />
            <CardContent>
              <LabeledTextBlock :label="'Name'">{{ Util.misc.fullName(Config.user.info) }}</LabeledTextBlock>
              <Spacer isBottom :size="3" />
              <LabeledTextBlock :label="'Email'"><a :href="'tel:'+Config.user.info.email">{{ Config.user.info.email }}</a></LabeledTextBlock>
              <Spacer isBottom :size="3" />
              <LabeledTextBlock :label="'Phone'"><a :href="'tel:'+Config.user.info.phone">{{ Config.user.info.phone | formatPhone }}</a></LabeledTextBlock>
              <Spacer isBottom :size="3" />
              <LabeledTextBlock :label="'Location'">{{ Config.user.info.city }}, {{ Config.user.info.state }}</LabeledTextBlock>
            </CardContent>
          </Card>
          <Card :slot="2" :heading="'Support'">
            <AvatarCardHeading :heading="Config.support.name" />
            <CardContent>
              <LabeledTextBlock :label="'Support'"><a :href="'mailto:'+Config.support.email">{{ Config.support.email }}</a></LabeledTextBlock>
              <Spacer isBottom :size="3" />
              <LabeledTextBlock :label="'Phone'"><a :href="'tel:'+Config.support.phone">{{ Config.support.phone }}</a></LabeledTextBlock>
              <Spacer isBottom :size="3" />
              <LabeledTextBlock :label="'Available'">{{ Config.support.available }}</LabeledTextBlock>
            </CardContent>
          </Card>
        </Grid>
      </SlideIn>
    </div>
  </PageContainer>
</template>

<script>
import { LoadingSpinner } from 'feedback';
import { Paragraph } from 'typography';
import { Card, CardContent, Grid, GridTest, PageHeader, PageContainer, SlideIn, Spacer } from 'layout';
import AppointmentCardInfo from './AppointmentCardInfo.vue';
import AvatarCardHeading from './AvatarCardHeading.vue';
import LabeledTextBlock from './LabeledTextBlock.vue';

export default {
  name: 'dashboard',
  components: { AppointmentCardInfo, AvatarCardHeading, Card, CardContent, Grid, GridTest, LabeledTextBlock, LoadingSpinner, PageHeader, PageContainer, Paragraph, SlideIn, Spacer },
  computed: {
    isDoneLoading() {
      return this.State('practitioners.data.all').length && this.State('appointments.wasRequested.upcoming') && !this.State('appointments.isLoading.upcoming');
    },
    shouldShowDoctorInfo() {
      return App.Config.user.isPatient && this.State('practitioners.userDoctor');
    },
    topRowColumnConfig() {
      return this.shouldShowDoctorInfo ? [{ l:'1of2' }, { l:'1of2' }] : [{ l:'1of1' }];
    },
    upcomingAppointments() {
      return this.State('appointments.data.upcoming').filter(a => a.attributes.status === 'pending');
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
};
</script>

<style lang="scss" scoped>
    .test {
        background: skyblue;
        text-align: center;
        padding: 1rem;
        text-transform: uppercase;
    }
</style>
