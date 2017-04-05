<template>
  <div>
    <signup-step :is="currentStep" :next-step="nextStep"></signup-step>
  </div>
</template>

<script>
  import Registration from './_components/Registration.vue';
  import Phone from './_components/Phone.vue';
  import Practitioner from './_components/Practitioner.vue';
  import Location from './_components/Location.vue';
  import DateTime from './_components/DateTime.vue';
  import Modal from './_components/Modal.vue';
  import Confirmation from './_components/Confirmation.vue';

  export default {
    name: 'Signup',
    components: {
      'registration': Registration,
      'phone': Phone,
      'practitioner': Practitioner,
      'location': Location,
      'datetime': DateTime,
      'modal': Modal,
      'confirmation': Confirmation
    }
  }
</script>

<style>

</style>
