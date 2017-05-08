<template>
  <aside :class="{ flyout: true, isactive: isActive }">
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
      <button class="flyout__item button main-action">Update Appointment</button><br>
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
      // activeDetails = are the details for this appointment already in view?
      // This is for toggling the flyout window if the same appointment is clicked
      this.$eventHub.$on('appointmentSelected', (details, activeDetails) => {
        this.isActive = !activeDetails;
      });
    }
  }
</script>

<style lang="scss" scoped>

</style>
