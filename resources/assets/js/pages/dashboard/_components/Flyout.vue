<template>
  <aside class="flyout">
    <header class="flyout__section">
      <span class="flyout__patientname">{{ details.patient_name }}</span>
      <span>with Dr. {{ details.doctor_name }}</span>
    </header>
    <section class="flyout__section">
      <span class="flyout__heading">contact</span>
      <a class="flyout__item" :href="'mailto:' + details.patient_email">{{ details.patient_email }}</a><br>
      <a class="flyout__item" :href="'tel:' + details.patient_phone">{{ details.patient_phone }}</a>
    </section>
    <section class="flyout__section">
      <span class="flyout__heading">booked on</span>
      <time class="flyout__item">{{ details.appointment_at }}</time>
    </section>
    <section class="flyout__section">
      <span class="flyout__heading">purpose</span>
      <p class="flyout__item">{{ details.appointment_purpose }}</p>
    </section>
    <section class="flyout__section flyout__ctas">
      <button class="flyout__item">Update Appointment</button><br>
      <a class="flyout__item" href="#cancel">Cancel Appointment</a>
    </section>
  </aside>
</template>

<script>
  export default {
    data() {
      return {
        isActive: false
      };
    },
    props: ['details'],
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
