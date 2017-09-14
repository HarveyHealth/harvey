<template>
  <Card :heading="Config.dashboard.titleDoctor" :sub-heading="subHeading" class="Column-lg-6">
    <div class="Card-Content">
      <p>{{ State('dashboard.practitioner.attributes.description', '') }}</p>
    </div>
  </Card>
</template>

<script>
import Card from './Card';
import { Layout } from '../../../base';

export default {
  components: { Card },
  computed: {
    subHeading() {
      return {
        avatar: App.State.dashboard.practitioner.attributes.picture_url || App.Config.misc.defaultUserImage,
        title: App.State.dashboard.practitioner.attributes.name
      }
    }
  }
}
</script>
