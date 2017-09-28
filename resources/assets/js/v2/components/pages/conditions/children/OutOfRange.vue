<template>
  <div>
    <SlideIn>
      <p>Unfortunately, we are unnable to service clients in your state yet, but we&rsquo;re working on it.</p>
      <p>We will add you to our newsletter and let you know as soon as we launch there.</p>
    </SlideIn>
  </div>
</template>

<script>
import { Util } from '../../../base';

export default {
  components: {
    SlideIn: Util.SlideIn
  }
}
</script>
