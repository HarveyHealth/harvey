<template>
  <Container>
    <MainHeader :heading="Config.dashboard.title"></MainHeader>
    <div class="pad-sm">
      <LoadingBox :is-loading="isLoading" :message="'Loading dashboard'" />
      <div v-if="!isLoading">
        <div class="Row gutter-sm">
          <div class="Card Column-lg-6">
            <div class="Card-Header">
              <h2 class="heading-2">Upcoming Appointments</h2>
            </div>
          </div>
          <CardPractitioner />
        </div>
        <div class="Row gutter-sm">
          <CardUser />
          <CardSupport />
        </div>
      </div>
    </div>
  </Container>
</template>

<script>
import { Layout, Structures, Util } from '../../base';
import Children from './children';

export default {
  components: {
    CardPractitioner: Children.CardPractitioner,
    CardSupport: Children.CardSupport,
    CardUser: Children.CardUser,
    Container: Layout.Container,
    GridColumn: Layout.GridColumn,
    GridRow: Layout.GridRow,
    LoadingBox: Util.LoadingBox,
    MainHeader: Structures.MainHeader,
  },
  computed: {
    isLoading() {
      if (App.Config.user.isPatient) {
        return !App.State.received.appointments && !App.State.received.practitioners;
      } else {
        return !App.State.received.practitioners;
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
