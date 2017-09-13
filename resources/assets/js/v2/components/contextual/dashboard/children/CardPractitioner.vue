<template>
  <Card :heading="Config.dashboard.titleDoctor" :sub-heading="subHeading">
    <div class="Card-Content">
      <p>{{ State('dashboard.practitioner.attributes.description', '') }}</p>
    </div>
  </Card>
</template>

<script>
import Card from './Card';

export default {
  components: { Card },
  data() { return {
    subHeading: {
      avatar: App.State.dashboard.practitioner.attributes.picture_url || App.Config.misc.defaultUserImage,
      title: App.State.dashboard.practitioner.attributes.name
    }
  }}
}
</script>
