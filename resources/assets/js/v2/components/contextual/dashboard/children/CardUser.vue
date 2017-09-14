<template>
  <Card :heading="Config.dashboard.titleDoctor" :sub-heading="subHeading" class="Column-lg-6">
    <div class="Card-Content space-children-md">
      <div class="space-children-xxs">
        <h4 class="heading-4">Email</h4>
        <a :href="'mailto:' + Config.user.info.email">{{ Config.user.info.email }}</a>
      </div>
      <div class="space-children-xxs">
        <h4 class="heading-4">Zip</h4>
        <p>{{ Config.user.info.zip }}</p>
      </div>
      <div class="space-children-xxs">
        <h4 class="heading-4">Phone</h4>
        <a :href="'tel:' + Config.user.info.phone">{{ Config.user.info.phone | formatPhone }}</a>
      </div>
      <div class="space-children-xxs">
        <h4 class="heading-4">Id</h4>
        <p>#{{ Config.user.info.id }}</p>
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
      avatar: App.Config.user.info.image_url,
      title: App.Util.misc.fullName(App.Config.user.info)
    }
  }}
}
</script>
