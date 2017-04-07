<template>
  <div class="container small">
    <h1 class="header-xlarge">{{ title }}</h1>
    <p class="large">{{ subtitle }}</p>
    <!-- <a class="button" @click.prevent="getStarted">Continue</a> -->
  </div>

  <!-- <form>
    <practitioner v-show="step === 1" />
    <phone v-show="step === 2" />
    <location v-show="step === 3" />
    <datetime v-show="step === 4" />
  </form> -->
</template>

<script>
// import Registration from './_components/Registration.vue';
// import Phone from './_components/Phone.vue';
  import Practitioner from './_components/Practitioner.vue';
// import Location from './_components/Location.vue';
// import DateTime from './_components/DateTime.vue';
// import Modal from './_components/Modal.vue';
// import Confirmation from './_components/Confirmation.vue';
  export default {
    name: 'GetStarted',
    data() {
      return {
        title: "We're starting the process",
        subtitle: 'Before talking to a doctor, we need some basic contact info, your choice of practitioner and a date/time you are available for a consultation. This should take less than 5 minutes.',
      }
    },
    components: {
      // 'phone': Phone,
      'practitioner': Practitioner,
    },
    methods: {
      getStarted() {
        this.$router.push('/get-started');
      }
    }
  }
</script>
