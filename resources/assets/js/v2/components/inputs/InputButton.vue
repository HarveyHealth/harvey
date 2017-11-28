<template>
  <button
    :class="'Button ' + config[type].class"
    :disabled="isDisabled || isProcessing"
    @click="onClick"
    :style="'width:' + width || 'auto'"
  >
    <LoadingSpinner
      v-if="isProcessing"
      class="inline margin-right-xxs"
      :color="config[type].loadingColor"
      :size="config[type].loadingSize" />
    <div v-else-if="isDone">
      <i :class="'margin-right-xxs inline fa ' + config[type].doneIcon" style="font-size:12px"></i>
      <span>{{ config[type].doneText }}</span>
    </div>
    <span v-else v-html="text"></span>
  </button>
</template>

<script>
import { LoadingSpinner } from 'feedback';

export default {
  props: {
    doneText: { type: String, default: '' },
    doneIcon: { type: String, default: 'fa-check' },
    isDisabled: Boolean,
    isDone: Boolean,
    isProcessing: Boolean,
    onClick: { type: Function, required: true },
    text: { type: String, required: true },
    type: { type: String, default: 'default' },
    width: String
  },
  components: {
    LoadingSpinner
  },
  data() {
    return {
      config: {
        default: {
          class: '',
          doneIcon: this.doneIcon,
          doneText: this.doneText,
          loadingColor: 'light',
          loadingSize: 'sm'
        },
        whiteFilled: {
          class: 'Button--white-filled',
          doneIcon: this.doneIcon,
          doneText: this.doneText,
          loadingColor: 'dark',
          loadingSize: 'sm'
        }
      }
    };
  }
};
</script>
