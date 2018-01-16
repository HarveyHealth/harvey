<template>
    <TheMask
        :class="classes"
        :disabled="isDisabled"
        :mask="mask"
        :placeholder="mask.split('').map(a => '●').join('')"
        :ref="'code_input_ref'"
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
        isAutoFocused: Boolean,
        isDisabled: Boolean,
        mask: {
            type: String,
            required: true
        },
        theme: {
            type: String,
            default: 'primary',
            validator(theme) {
                const validThemes = ['primary', 'inverse-dark', 'inverse-light'];

                return validThemes.indexOf(theme) > -1;
            }
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

    computed: {
        classes() {
            return {
                'code-input': true,
                [`${this.theme}`]: true
            };
        }
    },

    methods: {
        handleInput(v) {
            this.onInput(v);
        }
    },

    mounted() {
        if (this.isAutoFocused) {
            this.$refs.code_input_ref.$el.focus();
        }
    }
};
</script>

<style lang="scss" scoped>
    @import '~sass';

    .code-input {
        @extend %input--text-reset;

        border-radius: 6px;
        font-size: 28px;
        font-weight: 600;
        letter-spacing: 6px;
        padding: 6px 10px;
        text-align: center;

        // Input modes
        &.primary {
            background: $color-gray-5;
        }

        &.inverse-dark {
            background: transparent;
            border: 3px solid $color-copy;
        }

        &.inverse-light {
            background: transparent;
            border: 3px solid $color-white;
            color: $color-white;
        }
    }
</style>
