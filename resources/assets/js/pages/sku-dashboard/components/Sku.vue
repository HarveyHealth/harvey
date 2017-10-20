<template>
    <tr class="cell-wrap grabbable">
        <td class="sku-table__column sku-table__move-icon sort"><i class="fa fa-bars"></i></td>
        <td class="sku-table__column sku-table__sku-name">{{ sku.attributes.name }}</td>
        <td class="sku-table__column">{{ sku.attributes.lab_test_information.lab_name }}</td>
        <td class="sku-table__column">{{ sku.attributes.lab_test_information.sample }}</td>
        <td class="sku-table__column">{{ `${sku.attributes.lab_test_information.description.substr(0,15)}...` }}</td>
        <td class="sku-table__column">{{ `${sku.attributes.lab_test_information.quote.substr(0,15)}...` }}</td>
        <td class="sku-table__column">${{ sku.attributes.price }}</td>
        <td class="sku-table__column">${{ sku.attributes.cost }}</td>
        <td class="sku-table__column">{{ skuPublic }}</td>
    </tr>
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
