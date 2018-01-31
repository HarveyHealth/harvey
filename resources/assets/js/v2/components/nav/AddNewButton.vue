<template>
    <button @click="onClick" class="button main-action circle">
        <svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#addition"></use></svg>
    </button>
</template>

<script>
export default {
    props: {
        onClick: Function
    }
};
</script>

<style lang="scss" scoped>
    @import '~sass';
</style>
