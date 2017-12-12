<template>
    <TheMask
        class="code-input"
        :disabled="isDisabled"
        :mask="mask"
        :placeholder="mask.split('').map(a => '●').join('')"
        :style="{ width: width }"
        :type="type"
        :value="value || ''"
        @input="handleInput"
    />
</template>

<script>
import { TheMask } from 'vue-the-mask';

export default {
    props: {
        isDisabled: Boolean,
        mask: {
            type: String,
            required: true
        },
        type: {
            type: String,
            default: 'text'
        },
        onInput: {
            type: Function,
            default: () => {}
        },
        value: String,
        width: {
            type: String,
            default: '160px'
        }
    },
    components: {
        TheMask
    },
    methods: {
        handleInput(v) {
            this.onInput(v);
        }
    }
}
</script>

<style lang="scss" scoped>
    @import '~sass';

    .code-input {
        @extend %input--text-reset;

        background: $color-gray-5;
        border-radius: 6px;
        font-size: 28px;
        font-weight: 600;
        letter-spacing: 6px;
        padding: 6px 10px;
        text-align: center;
    }
</style>
