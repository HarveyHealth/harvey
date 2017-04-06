<template>
  <div>
    <form method="post" action="/practitioner" @submit.prevent="onSubmit">
      <registration v-show="true" />
      <practitioner />
      <phone />
      <location />
      <datetime />
      <div class="text-centered">
        <input type="submit" class="button" value="Submit">
      </div>
    </form>
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
    step: 1,
    components: {
      'registration': Registration,
      'phone': Phone,
      'practitioner': Practitioner,
      'location': Location,
      'datetime': DateTime,
      'modal': Modal,
      'confirmation': Confirmation
    },
    methods: {
      onSubmit() {
        console.log("form submitted");
      }
    }
  }
</script>

<style>

</style>
