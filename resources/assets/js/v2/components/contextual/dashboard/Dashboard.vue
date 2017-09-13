<template>
  <Container>
    <MainHeader :heading="Config.dashboard.title"></MainHeader>
    <div class="pad-sm">
      <LoadingBox :is-loading="isLoading" :message="'Loading dashboard'" />
      <div v-if="!isLoading">
        <div class="Card">
          <div class="Card-Header">
            <h2 class="heading-2">Upcoming Appointments</h2>
          </div>
          <div class="Card-Content">
            <p>This is card content</p>
          </div>
          <div class="Card-Content border-top">
            <p>This is card content</p>
          </div>
          <div class="Card-Content border-top">
            <p>This is card content</p>
          </div>
        </div>
      </div>
    </div>
  </Container>
</template>

<script>
import { Layout, Structures, Util } from '../../base';

export default {
  components: {
    Container: Layout.Container,
    LoadingBox: Util.LoadingBox,
    MainHeader: Structures.MainHeader,
  },
  computed: {
    isLoading() {
      if (App.Config.user.isPatient) {
        return !App.State.received.appointments && !App.State.received.practitioners;
      } else {
        return !App.State.received.appointments;
      }
    }
  },
  beforeMount() {
    App.Logic.misc.setCurrentPage(App.Config.dashboard.title);
  }
}
</script>
