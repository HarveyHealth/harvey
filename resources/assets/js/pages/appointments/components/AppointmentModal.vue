<template>
  <div v-show="isActive" class="modal-wrapper appointment-modal" @click="handleOverlayClick($event)">
    <div class="modal">
      <h3 class="modal-header">{{ title }}</h3>
      <button @click="handleCancelClick()" class="button button--cancel modal-close">
        <svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#close"></use></svg>
      </button>
      <p v-for="(value, key) in text">
        <strong>{{ key }}</strong>: {{ value }}
      </p>
      <div class="modal-button-container">
        <button @click="handleAffirmClick()" class="button">{{ affirm }}</button>
        <button @click="handleCancelClick()" class="button button--cancel">Go Back</button>
        <p v-if="note !== ''">{{ note }}</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['affirm', 'affirmEvent', 'note', 'text', 'title'],
  data() {
    return {
      isActive: false
    }
  },
  methods: {
    handleOverlayClick(e) {
      if (/.*appointment-modal.*/.test(e.target.className)) {
        this.isActive = false;
      }
    },
    handleCancelClick() {
      this.isActive = false;
    },
    handleAffirmClick() {
      this.isActive = false;
      this.$eventHub.$emit(this.affirmEvent);
    }
  },
  mounted() {
    this.$eventHub.$on('callAppointmentModal', () => this.isActive = true)
  },
  destroyed() {
    this.$eventHub.$off('callAppointmentModal');
  }
}
</script>
