<template>
  <aside class="flyout">
    <header class="flyout__section">
      <span class="flyout__patientname">Patient Name</span>
      <span>with Doctor Name, MD</span>
    </header>
    <section class="flyout__section">
      <span class="flyout__heading">contact</span>
      <a class="flyout__item" href="mailto:patient@email.com">patient@email.com</a><br>
      <a class="flyout__item" href="tel:1231231234">123-123-1234</a>
    </section>
    <section class="flyout__section">
      <span class="flyout__heading">booked on</span>
      <time class="flyout__item" >Someday, Monthish 4, 2017</time>
    </section>
    <section class="flyout__section flyout__ctas">
      <button class="flyout__item" >Update Appointment</button><br>
      <a class="flyout__item" href="#cancel">Cancel Appointment</a>
    </section>
  </aside>
</template>

<script>
  export default {
    data() {
      return {

      };
    },
    props: [],
    components: {

    },
    methods: {

    },
    computed: {

    },
    mounted() {

    }
  }
</script>

<style lang="scss" scoped>

</style>
