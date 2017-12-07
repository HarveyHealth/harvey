<template>
    <div
        @click="onClick ? onClick() : false"
        :class="{ overlay: true, 'is-active': isActive }"
    />
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

    .overlay {
        background: rgba(0,0,0,0);
        position: absolute;
        transition: background 200ms ease-in-out;

        &.is-active {
            background: $color-fade-dark;
            bottom: 0;
            left: 0;
            position: fixed;
            right: 0;
            top: 0;
            z-index: 8000;
        }
    }
</style>
