<template>
    <button :class="{ 'bubble': true, 'is-active': isActive }" @click="onClick">
        <slot></slot>
    </button>
</template>

<script>
export default {
    props: {
        isActive: Boolean,
        onClick: Function
    }
}
</script>

<style lang="scss" scoped>
    @import '~sass';

    .bubble {
        @extend %button-reset;

        background: $color-gray-4;
        border: 1px solid transparent;
        border-radius: 50%;
        color: $color-copy;
        height: 40px;
        font-size: 12px;
        font-weight: 600;
        width: 40px;

        &.is-active {
            background: $color-accent-dark;
            color: $color-white;
        }
    }
</style>
