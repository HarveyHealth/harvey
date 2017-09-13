<template>
  <Container>
    <MainHeader :heading="Config.dashboard.title"></MainHeader>
    <div class="pad-sm">
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
  </Container>
</template>

<script>
import { Layout, Structures } from '../../base';

export default {
  components: {
    Container: Layout.Container,
    MainHeader: Structures.MainHeader
  },
  beforeMount() {
    App.Logic.misc.setCurrentPage(App.Config.dashboard.title);
  }
}
</script>
