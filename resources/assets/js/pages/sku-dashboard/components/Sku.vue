<template>
    <div class="sku-table__sku">
        <div class="sku-table__column sku-table__move-icon"><i class="fa fa-bars"></i></div>
        <div class="sku-table__column sku-table__sku-name">{{ sku.attributes.name }}</div>
        <div class="sku-table__column">{{ sku.attributes.lab_test_information.lab_name }}</div>
        <div class="sku-table__column">{{ sku.attributes.lab_test_information.sample }}</div>
        <div class="sku-table__column">{{ `${sku.attributes.lab_test_information.description.substr(0,15)}...` }}</div>
        <div class="sku-table__column">{{ `${sku.attributes.lab_test_information.quote.substr(0,15)}...` }}</div>
        <div class="sku-table__column">{{ sku.attributes.price }}</div>
        <div class="sku-table__column">{{ sku.attributes.cost }}</div>
        <div class="sku-table__column">{{ skuPublic }}</div>
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
    computed: {
        skuPublic() {
            return this.sku.attributes.lab_test_information.published_at ? "Yes" : "No";
        }
    },
    props: {
        sku: {
            type: Object,
        },
        index: {
            type: Number,
        },
    }
}
</script>

<style lang="scss">
    .sku-table {
        &__sku {
            display: flex;
            justify-content: space-between;
            height: 40px;
            background-color: white;
            border-bottom: 1px solid grey;
            align-items: center;
        }

        &__column {
            min-width: 9rem;
        }

        &__move-icon {
            min-width: 4%;
            display: flex;
            justify-content: center;
        }
    }
</style>
