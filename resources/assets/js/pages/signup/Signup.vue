<template>
  <div>
    <form method="post" action="/" @submit.prevent="onSubmit">
      <registration />
      <div class="text-centered">
        <input type="submit" class="button" value="Submit">
      </div>
    </form>

    <!-- <form>
      <practitioner v-show="step === 1" />
      <phone v-show="step === 2" />
      <location v-show="step === 3" />
      <datetime v-show="step === 4" />
    </form> -->
  </div>
</template>

<script>
  import Registration from './_components/Registration.vue';
  // import Phone from './_components/Phone.vue';
  // import Practitioner from './_components/Practitioner.vue';
  // import Location from './_components/Location.vue';
  // import DateTime from './_components/DateTime.vue';
  // import Modal from './_components/Modal.vue';
  // import Confirmation from './_components/Confirmation.vue';

  const signupSteps = 4;

  export default {
    name: 'Signup',
    data() {
      return {
        step: 1,
      }
    },
    components: {
      'registration': Registration,
      // 'phone': Phone,
      // 'practitioner': Practitioner,
      // 'location': Location,
      // 'datetime': DateTime,
      // 'modal': Modal,
      // 'confirmation': Confirmation
    },
    methods: {
      onSubmit() {
        console.log("form submitted");
        axios.post('api/v1/users', {
          email: 'test@test.com',
          terms: false,
        })
        .then(response => {
          console.log(reponse);
        })
        // should be used to send rest of data to back-end
      },
      //
      // incrementStep() {
      //   if (this.step != signupSteps) this.step++;
      // },
      //
      // submitAccountInformation(accountData) {
      //   console.log('submitting account information...', accountData);
      //   this.incrementStep();
      // }
    },
  }
</script>

<style>

</style>
