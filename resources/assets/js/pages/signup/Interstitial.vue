<template>
  <div>
    <div class="container small message-container">
      <div v-if="zipInRange" class="container small">
        <h1 class="header-xlarge">You did it!</h1>
        <p class="large">Before talking to a doctor, we need some basic contact info, your choice of practitioner and a date/time you are available for a consultation. This should take less than 5 minutes.</p>
      </div>

      <div v-if="!zipInRange" class="container small">
        <h1 class="header-xlarge">Nope</h1>
        <p class="large">You don't live in a servicable location.</p>
      </div>
    </div>

    <div class="text-centered">
      <a class="button" @click.prevent="nextStep">Continue</a>
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
          this.$router.push('/schedule');
        } else {
          window.location.href = '/'; // home for now
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
  .message-container {
    margin-bottom: 25px;
  }
</style>
