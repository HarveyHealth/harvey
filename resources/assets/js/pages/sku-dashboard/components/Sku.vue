<template>
    <tr class="cell-wrap grabbable" :class="isSelected">
        <td class="sku-table__column sku-table__move-icon"><i class="fa fa-bars"></i></td>
        <td class="sku-table__column sku-table__move-icon">{{ sku.id }}</td>
        <td class="sku-table__column">{{ sku.attributes.lab_test_information.lab_name }}</td>
        <td class="sku-table__column sku-table__sku-name">{{ sku.attributes.name }}</td>
        <td class="sku-table__column">{{ sku.attributes.lab_test_information.sample }}</td>
        <td class="sku-table__column">{{ `${sku.attributes.lab_test_information.description.substr(0,15)}...` }}</td>
        <td class="sku-table__column">{{ `${sku.attributes.lab_test_information.quote.substr(0,15)}...` }}</td>
        <td class="sku-table__column">${{ sku.attributes.price }}</td>
        <td class="sku-table__column">${{ sku.attributes.cost }}</td>
        <td class="sku-table__column">{{ visibilityFriendlyName }}</td>
    </tr>
</template>

<script>
export default {
    data() {
        return {};
    },
    components: {
    },
    methods: {
    },
    computed: {
        isSelected() {
            if (this.selectedSku) {
                return this.sku.id === this.selectedSku.id ? 'is-selected' : '';
            }
            return '';
        },
        visibilityFriendlyName() {
            let visibility = this.sku.attributes.lab_test_information.visibility;
            let translation = {
                public: 'All',
                patients: 'Patient, Practitioners and Admins',
                practitioners: 'Practitioners and Admins',
                admins: 'Only Admins'
            };

            return translation[visibility] || 'Unknown';
        }
    },
    props: {
        sku: {
            type: Object
        },
        index: {
            type: Number
        },
        selectedSku: {
            type: Object
        }
    }
};
</script>
