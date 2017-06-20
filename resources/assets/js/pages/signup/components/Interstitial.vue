<template>
  <div>
    <div class="container small message-container-interstitial">

      <div v-if="zipInRange" class="container small">
        <img src="/images/signup/interstitial.png" class="interstitial" alt="">
        <h1 class="header-xlarge">Success! Let's get started.</h1>
        <p class="large">Before booking a physician, we need some basic contact info, your choice of practitioner and a date and time you are available for a phone consultation. This should take about 5 minutes.</p>
      </div>

      <div v-if="!zipInRange" class="container small">
        <img src="/images/signup/tree.png" class="registration-tree" alt="">
        <h1 class="header-xlarge">Oops! We are not in your state yet.</h1>
        <p class="large">Unfortunately, we do not have any licensed physicians in your state yet. We will email you once we launch in your area.</p>
      </div>

    </div>

    <div class="text-centered">
      <a href="/dashboard" class="button" @click.prevent="nextStep">Let's Go!</a>
    </div>

  </div>
</template>

<script>
  export default {
    name: 'Confirmation',
    props: {
      'zipInRange': {
        type: Boolean,
        required: true,
      },
    },
    methods: {
      nextStep() {
        if (this.zipInRange) {
          window.location.href = '/dashboard';
        } else {
          window.location.href = '/'; // home for now
        }
      }
    },
    mounted() {
      if (this.$root.$data.environment === 'production' || this.$root.$data.environment === 'prod') {
        this.$ma.trackEvent({
            fb_event: 'PageView',
            type: 'product',
            category: 'clicks',
            properties: { laravel_object: Laravel.user }
        });
      }
    }
  }
</script>
