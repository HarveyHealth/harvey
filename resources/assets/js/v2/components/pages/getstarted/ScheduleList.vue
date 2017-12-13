<template>
    <ol class="list mb4 mt2">
        <slot></slot>
    </ol>
</template>

<script>
export default {}
</script>

<style lang="scss">
    @import '~sass';

    .list {
        @extend %list-reset;

        li {
            display: inline-block;
            padding: 5px 3px 16px;
            text-align: center;
            width: 20%;
        }
    }
</style>
