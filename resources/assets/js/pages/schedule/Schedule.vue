<template>
  <form @submit.prevent="onSubmit">
    <practitioner v-show="step === 1" />
    <phone v-show="step === 2" />
    <datetime v-show="step === 3" />
  </form>
</template>

<script>
  import Practitioner from './_components/Practitioner.vue';
  import Phone from './_components/Phone.vue';
  import DateTime from './_components/DateTime.vue';
  export default {
    name: 'Schedule',
    data() {
      return {
        title: "We're starting the process",
        subtitle: 'Before talking to a doctor, we need some basic contact info, your choice of practitioner and a date/time you are available for a consultation. This should take less than 5 minutes.',
        step: 3,
        practitioner: null,
      }
    },
    components: {
      'datetime': DateTime,
      'phone': Phone,
      'practitioner': Practitioner,
    },
    methods: {
      next() {
        this.step ++; // simply increment the steps to move through the form states
      },
      onSubmit() {
        console.log('submitting');
        this.$router.push('/confirmation');
      }
    }
  }
</script>
