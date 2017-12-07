<template>
    <button
        :class="'Button ' + config[mode].class"
        :disabled="isDisabled || isProcessing"
        @click="onClick"
        :style="'width:' + width || 'auto'"
    >
        <LoadingSpinner
            v-if="isProcessing"
            class="di margin-right-xxs"
            :color="config[mode].loadingColor"
            :size="config[mode].loadingSize"
        />
        <div v-else-if="isDone">
            <i :class="'margin-right-xxs inline fa ' + config[mode].doneIcon" style="font-size:12px"></i>
            <span>{{ config[mode].doneText }}</span>
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
        mode: { type: String, default: 'default' },
        onClick: { type: Function, required: true },
        text: { type: String, required: true },
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
                gray: {
                    class: 'gray-button',
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

<style lang="scss" scoped>
    @import '~sass';

    .gray-button {
        background: $color-copy-muted-1;
        border-color: $color-copy-muted-1;
    }
</style>
