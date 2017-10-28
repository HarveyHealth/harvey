<template>
  <div>
    <PageContainer>
      <PageHeader :heading="'Dashboard'" />
    </PageContainer>
  </div>
</template>

<script>
import { PageHeader, PageContainer } from 'layout';

export default {
  name: 'dashboard',
  components: { PageHeader, PageContainer },
  mounted() {
    App.setState('misc.currentPage', 'dashboard');
  }
}
</script>
