<template>
    <div :class="{ 'input-container': true, 'has-error': this.error }">
        <input
            :class="inputClasses"
            :maxlength="maxlength"
            :name="name"
            :type="type"
            :placeholder="placeholder"
            :value="value || ''"
            @input="onInput"
            @blur="$event => { if (onBlur) onBlur($event) }"
        />
        <div class="error-msg f7 ph2 pv1" v-html="error"></div>
    </div>
</template>

<script>
export default {
    props: {
        error: String,
        maxlength: String,
        mode: String,
        name: String,
        onBlur: Function,
        onInput: Function,
        placeholder: String,
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
            }
        }
    }
}
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
    }

    .error-msg {
        color: $color-error;
        height: 22px;
        opacity: 0;
        transition: opacity 200ms ease-in-out;
    }

    .has-error {
        .text-input:focus,
        .text-input.bare:focus {
            border-color: $color-error;
        }

        .error-msg {
            opacity: 1;
        }
    }


</style>
