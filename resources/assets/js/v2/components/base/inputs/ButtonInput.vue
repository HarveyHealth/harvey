<template>
  <button :class="'button ' + config[type].class" :disabled="isDisabled" @click="onClick">
    <Loading v-if="isProcessing" :color="config[type].loadingColor" :size="config[type].loadingSize" />
    <i v-if="isDone" :class="'fa ' + config[type].doneIcon">{{ config[type].doneText }}</i>
    <span v-else>{{ text }}</span>
  </button>
</template>

<script>
import Util from '../util';

export default {
  props: {
    configDone: { type: Object, default: function() { return {}; } },
    isDisabled: Boolean,
    isDone: Boolean,
    isProcessing: Boolean,
    onClick: { type: Function, required: true },
    text: { type: String, required: true },
    type: { type: String, default: 'default' }
  },
  data() {
    return {
      config: {
        default: {
          class: '',
          doneIcon: this.configDone.icon || 'fa-check',
          doneText: this.configDone.text || 'Complete',
          loadingColor: 'light',
          loadingSize: 'sm',
        }
      }
    }
  },
  components: {
    Loading: Util.Loading,
  },
}
</script>
