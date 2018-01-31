<template>
    <PageContainer>
        <PageHeader heading="Messages">
            <AddNewButton :onClick="() => {}" />
        </PageHeader>
    </PageContainer>
</template>

<script>
import { PageHeader, PageContainer } from 'layout';
import { AddNewButton } from 'nav';

export default {
    name: 'messages',
    components: {
        PageContainer,
        PageHeader,
        AddNewButton,
    },
    mounted() {
        App.State.misc.currentPage = 'messages';
    }
};
</script>

<style lang="scss" scoped>
    @import '~sass';

</style>