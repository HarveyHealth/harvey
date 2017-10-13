<template>
    <div class="sku-table__sku">
        <div class="sku-table__sku-name">{{ sku.attributes.name }}</div>
        <div>{{ sku.attributes.lab_test_information.lab_name }}</div>
        <div>{{ sku.attributes.lab_test_information.sample }}</div>
        <div>{{ `${sku.attributes.lab_test_information.description.substr(0,15)}...` }}</div>
        <div>{{ `${sku.attributes.lab_test_information.quote.substr(0,15)}...` }}</div>
        <div>{{ sku.attributes.price }}</div>
        <div>{{ sku.attributes.price }}</div>
    </div>
</template>

<script>
export default {
    data() {
        return {

        }
    },
    components: {
    },
    methods: {
    },
    props: {
        sku: {
            type: Object,
        },
    }
}
</script>

<style lang="scss">
    .sku-table {
        &__sku {
            display: flex;
            justify-content: space-between;
        }

        div {
            min-width: 10rem;
        }
    }
</style>
