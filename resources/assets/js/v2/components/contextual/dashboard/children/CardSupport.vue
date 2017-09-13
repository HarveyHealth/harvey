<template>
  <Card :heading="Config.dashboard.support.heading" :sub-heading="subHeading">
    <div class="Card-Content space-children-md">
      <div class="space-children-xxs">
        <h4 class="heading-4">Support</h4>
        <a :href="'mailto:' + Config.dashboard.support.email">{{ Config.dashboard.support.email }}</a>
      </div>
      <div class="space-children-xxs">
        <h4 class="heading-4">Phone</h4>
        <a :href="'tel:' + Config.dashboard.support.phone">{{ Config.dashboard.support.phone }}</a>
      </div>
      <div class="space-children-xxs">
        <h4 class="heading-4">Available</h4>
        <p>{{ Config.dashboard.support.available }}</p>
      </div>
    </div>
  </Card>
</template>

<script>
import Card from './Card';

export default {
  components: { Card },
  data() { return {
    subHeading: {
      avatar: App.Config.misc.defaultUserImage,
      title: App.Config.dashboard.support.name
    }
  }}
}
</script>
