<template>
    <div class="card empty-card">
        <p class="copy-muted font-md font-italic">
            {{ message }}
        </p>
    </div>
</template>

<script>
export default {
    props: {
        message: String
    }
};
</script>

<style lang="scss" scoped>
    @import '~sass';
</style>