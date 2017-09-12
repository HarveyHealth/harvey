<template>
  <Container>
    <MainHeader :heading="Config.dashboard.title"></MainHeader>
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
