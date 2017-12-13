<template>
    <div :class="{ 'input-container': true, 'has-error': error, 'has-success': success }">
        <input
            :class="inputClasses"
            :disabled="disabled"
            :maxlength="maxlength"
            :name="name"
            :type="type"
            :placeholder="placeholder"
            :value="value || ''"
            @input="onInput"
            @blur="$event => { if (onBlur) onBlur($event) }"
            ref="text_input"
        />
        <div class="feedback f7 ph2 pv1">
            <span v-if="error">{{ error }}</span>
            <span v-if="success"><i class="fa fa-check mr1"></i>{{ success }}</span>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        disabled: Boolean,
        error: String,
        maxlength: String,
        mode: String,
        name: String,
        onBlur: Function,
        onInput: Function,
        placeholder: String,
        success: String,
        type: {
            type: String,
            default: 'text'
        },
        value: {
            type: String,
            default: ''
        }
    },
    computed: {
        inputClasses() {
            return {
                'text-input': true,
                'pa2': true,
                'bare': this.mode === 'bare'
            };
        }
    },
    methods: {
        focus() {
            this.$refs.text_input.focus();
        }
    }
};
</script>

<style lang="scss" scoped>
    @import '~sass';

    .text-input {
        @extend %input--text-reset;

        border: 1px solid $color-gray-5;
        border-radius: 3px;
        color: $color-copy;
        font-size: 1rem;
        width: 100%;

        &.bare {
            border: none;
            border-bottom: 1px solid $color-gray-5;
            border-radius: 0;
        }

        &:focus,
        &.bare:focus {
            border-color: $color-accent-dark;
        }

        &:disabled {
            color: $color-copy-muted-1;
        }
    }

    .feedback {
        height: 22px;
        opacity: 0;
        transition: opacity 200ms ease-in-out;
    }

    .has-error .feedback,
    .has-success .feedback {
        opacity: 1;
    }

    .has-error {
        .text-input:focus,
        .text-input.bare:focus {
            border-color: $color-error;
        }

        .feedback {
            color: $color-error;
        }
    }

    .has-success .feedback {
        color: $color-copy;
        font-style: italic;

        i { color: $color-good; }
    }
</style>
