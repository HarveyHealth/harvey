<template>
  <div class="container small">
    <h1 class="header-xlarge">{{ title }}</h1>
    <p class="large">{{ subtitle }}</p>
    <a class="button" @click.prevent="getStarted">Continue</a>
  </div>
</template>

<script>
  export default {
    name: 'Confirmation',
    data() {
      return {
        title: 'You did it!',
        subtitle: 'Before talking to a doctor, we need some basic contact info, your choice of practitioner and a date/time you are available for a consultation. This should take less than 5 minutes.',
      }
    },
    props: {
      'zipInRange': {
        type: Boolean,
        required: true,
      },
    },
    methods: {
      getStarted() {
        this.$router.push('/get-started');
      }
    }
  }
</script>
