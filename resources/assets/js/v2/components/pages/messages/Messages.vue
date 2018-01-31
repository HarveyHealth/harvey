<template>
    <PageContainer>
        <PageHeader heading="Messages">
            <AddNewButton :onClick="() => {}" />
        </PageHeader>
        <CardMessage message="You do not have any messages." />
        <CardMessage message="Your messages are loading..." />
    </PageContainer>
</template>

<script>
import { PageHeader, PageContainer, CardMessage } from 'layout';
import { AddNewButton } from 'nav';

export default {
    name: 'messages',
    components: {
        PageContainer,
        PageHeader,
        AddNewButton,
        CardMessage
    },
    mounted() {
        App.State.misc.currentPage = 'messages';
    }
};
</script>

<style lang="scss" scoped>
    @import '~sass';

</style>