<template>
  <form @submit.prevent="onSubmit">
    <practitioner v-show="step === 1" />
    <phone v-show="step === 2" />
    <datetime v-show="step === 3" />
    <confirmation v-show="step === 4" />
  </form>
</template>

<script>
  import Practitioner from './_components/Practitioner.vue';
  import Phone from './_components/Phone.vue';
  import DateTime from './_components/DateTime.vue';
  import Confirmation from './_components/Confirmation.vue';
// import Location from './_components/Location.vue';
// import Modal from './_components/Modal.vue';
  export default {
    name: 'GetStarted',
    data() {
      return {
        title: "We're starting the process",
        subtitle: 'Before talking to a doctor, we need some basic contact info, your choice of practitioner and a date/time you are available for a consultation. This should take less than 5 minutes.',
        step: 1,
      }
    },
    components: {
      'confirmation': Confirmation,
      'datetime': DateTime,
      'phone': Phone,
      'practitioner': Practitioner,
    },
    methods: {
      onSubmit() {
        // push to API
      },

      next() {
        console.log(this);
        this.step ++;
      }
    }
  }
</script>
