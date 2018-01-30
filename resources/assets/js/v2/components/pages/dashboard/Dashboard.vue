<template>
  <PageContainer>
    <PageHeader :heading="Store.isAdmin ? 'Admin Dashboard' : 'Dashboard'" />
    <div class="mw8 pa2 pa3-m">
      <NotificationPopup
        :active='isNotificationActive'
        comes-from='top-right'
        symbol='&#10003;'
        :text='notificationText'
      />
      <LoadingSpinner v-if="!isDoneLoading" class="mt3" />
      <SlideIn v-else>
          <SlideIn :to="'right'" v-if="shouldShowIntakeAlert">
              <Card isAlert class="alert mb2 mb3-m">
                  <CardContent class="pt4">
                      <Grid :columns="[{m:8}, {m:4}]" :gutters="{s:3}">
                          <Paragraph :slot="1" class="tc tl-m white">Please note: you must finish your patient intake form before your first appointment</Paragraph>
                          <div :slot="2" class="tc tr-m">
                              <InputButton :href="'/intake'" :mode="'inverse'" :text="'Intake Form'" />
                          </div>
                      </Grid>
                  </CardContent>
              </Card>
          </SlideIn>
        <Grid :columns="topRowColumnConfig" :gutters="{ s:2, m:3 }">
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
            <AvatarCardHeading :heading="State.practitioners.userDoctor.attributes.name" />
            <CardContent>
              <Paragraph :weight="'thin'">{{ State.practitioners.userDoctor.attributes.description }}</Paragraph>
            </CardContent>
          </Card>
        </Grid>
        <Grid :columns="[{ l:6 }, { l:6 }]" :gutters="{ s:2, m:3 }">
          <Card :slot="1" :heading="'Contact Info'">
            <AvatarCardHeading :heading="Util.misc.fullName(Config.user.info)" />
            <CardContent>
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
import { InputButton } from 'inputs';
import { LoadingSpinner } from 'feedback';
import { Card, CardContent, Grid, PageHeader, PageContainer, SlideIn, Spacer } from 'layout';
import { Paragraph } from 'typography';

import AppointmentCardInfo from './AppointmentCardInfo.vue';
import AvatarCardHeading from './AvatarCardHeading.vue';
import LabeledTextBlock from './LabeledTextBlock.vue';
import NotificationPopup from '../../../../commons/NotificationPopup.vue';

export default {
    name: 'dashboard',
    components: {
        AppointmentCardInfo,
        AvatarCardHeading,
        Card,
        CardContent,
        Grid,
        InputButton,
        LabeledTextBlock,
        LoadingSpinner,
        NotificationPopup,
        PageContainer,
        PageHeader,
        Paragraph,
        SlideIn,
        Spacer
    },
    data() {
        return {
            isNotificationActive: false,
            notificationText: ''
        };
    },
    computed: {
        isDoneLoading() {
            return this.State.practitioners.data.all.length &&
                this.State.appointments.wasRequested.upcoming &&
                !this.State.appointments.isLoading.upcoming;
        },
        pageHeading() {
            return App.Config.user.isAdmin ? 'Admin Dashboard' : 'Dashboard';
        },
        shouldShowDoctorInfo() {
            return App.Config.user.isPatient && this.State.practitioners.userDoctor;
        },
        shouldShowIntakeAlert() {
            return  Store.isPatient &&
                    Store.users.hasRequestedIntake &&
                    !Store.isLoadingIntake &&
                    !Store.users.intake.self;
        },
        topRowColumnConfig() {
            return this.shouldShowDoctorInfo ? [{ l:6 }, { l:6 }] : [{ l:12 }];
        },
        upcomingAppointments() {
            return this.State.appointments.data.upcoming.filter(a => a.attributes.status === 'pending');
        }
    },
    mounted() {
        Store.currentPage = 'dashboard';

        if (!this.State.users.intake.wasRequested) {
            App.Http.users.getPatientIntake(App.Config.user.info.id);
        }

        if (!this.State.appointments.wasRequested.upcoming) {
            App.Http.appointments.getUpcoming(App.Http.appointments.getUpcomingResponse);
        }

        if (!this.State.practitioners.wasRequested) {
            App.Http.practitioners.get(App.Http.practitioners.getResponse);
        }

        if (undefined !== Laravel.app.message) {
            this.notificationText = Laravel.app.message;
            this.isNotificationActive = true;
            setTimeout(() => this.isNotificationActive = false, 8000);
            Laravel.app.message = undefined;
        }
  }
};
</script>

<style lang="scss" scoped>
    @import '~sass';

    .alert {
        border-left: 7px solid;
    }
</style>
