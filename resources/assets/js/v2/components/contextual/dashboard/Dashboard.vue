<template>
  <Container>
    <MainHeader :heading="Config.dashboard.title"></MainHeader>
    <div class="pad-sm max-width-xl">
      <LoadingBox :is-loading="isLoading" :message="'Loading dashboard'" />
      <SlideIn v-if="!isLoading">
        <!-- <Alert v-if="!Config.user.info.intake_completed_at" class="margin-bottom-sm" /> -->
        <div :class="{ 'Row gutter-sm': Config.user.isPatient }">
          <div :class="appointmentsWrap">
            <CardAppointments :heading="'Upcoming Appointments'" :appointments="State('data.appointments.upcoming')" />
            <CardAppointments :heading="'Recent Appointments'" :appointments="State('data.appointments.recent')" />
          </div>
          <CardPractitioner v-if="Config.user.isPatient" />
        </div>
        <div class="Row gutter-sm">
          <CardUser />
          <CardSupport />
        </div>
      </SlideIn>
    </div>
  </Container>
</template>

<script>
import { Layout, Structures, Util } from '../../base';
import Children from './children';

export default {
  components: {
    Alert: Children.Alert,
    CardAppointments: Children.CardAppointments,
    CardPractitioner: Children.CardPractitioner,
    CardSupport: Children.CardSupport,
    CardUser: Children.CardUser,
    Container: Layout.Container,
    LoadingBox: Util.LoadingBox,
    MainHeader: Structures.MainHeader,
    SlideIn: Util.SlideIn
  },
  computed: {
    appointmentsWrap() {
      return {
        'Row gutter-sm': !App.Config.user.isPatient,
        'Column-lg-6 space-children-sm': App.Config.user.isPatient
      }
    },
    isLoading() {
      if (App.Config.user.isPatient) {
        return App.State.isLoading.appointments || App.State.isLoading.practitioners;
      } else {
        return App.State.isLoading.appointments;
      }
    }
  },
  beforeMount() {
    App.Logic.misc.setCurrentPage(App.Config.dashboard.title);
    App.Http.appointments.get(App.Http.appointments.getResponse);
    if (App.Config.user.isPatient) {
      App.Http.practitioners.get(App.Http.practitioners.getResponse);
    }
  }
}
</script>
