<template>
  <PageContainer>
    <PageHeader :heading="heading" />
    <Row2 :at="'large'" :columns="columns">
      <h3 slot="1" class="bg-blue ma0">First</h3>
      <Row2 slot="2" :columns="[{'not-small':'2of3'},{'not-small':'1of3'}]" class="bg-black-10">
        <h4 slot="1" class="bg-red pa3 ma0">Inner</h4>
        <h4 slot="2" class="pa5 ma0">Second</h4>
      </Row2>
    </Row2>
  </PageContainer>
</template>

<script>
import { PageHeader, PageContainer, Row2 } from 'layout';

export default {
  name: 'dashboard',
  components: {
    PageHeader,
    PageContainer,
    Row2
  },
  data() {
    return {
      heading: `${App.Config.user.isAdmin ? 'Admin ' : ''}Dashboard`,
      columns: [
        { large: '1of3' },
        { large: '2of3' }
      ]
    }
  },
  mounted() {
    App.setState('misc.currentPage', 'dashboard');
  }
}
</script>
