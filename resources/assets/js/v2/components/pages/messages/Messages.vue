<template>
    <PageContainer>
        <PageHeader heading="Messages" />
        <LoadingSpinner />
    </PageContainer>
</template>

<script>
import { LoadingSpinner } from 'feedback';
import { PageHeader, PageContainer } from 'layout';

export default {
    name: 'messages',
    components: {
        PageContainer,
        PageHeader,
        LoadingSpinner
    },
    mounted() {
        App.State.misc.currentPage = 'messages';
    }
};
</script>

<style lang="scss" scoped>
    @import '~sass';

</style>