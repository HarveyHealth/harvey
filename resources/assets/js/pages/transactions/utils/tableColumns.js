import tableSort from '../../../utils/methods/tableSort';

export default [
    {
        key: 'id',
        name: 'ID',
        width: '10%',
        sort: tableSort.byDate('date')
    },
    {
        key: 'date',
        name: 'Date',
        width: '10%',
        sort: tableSort.byDate('date')
    },
    {
        key: 'service',
        name: 'Service',
        width: '10%',
        sort: tableSort.byString('service')
    },
    {
        key: 'details',
        name: 'Details',
        width: '10%',
        sort: tableSort.byString('details')
    },
    {
        key: 'price',
        name: 'Price',
        width: '10%',
        sort: tableSort.byString('price')
    },
    {
        key: 'discount',
        name: 'Discount',
        width: '10%',
        sort: tableSort.byString('discount')
    },
    {
        key: 'total',
        name: 'Total',
        width: '10%',
        sort: tableSort.byString('total')
    },
    {
        key: 'card',
        name: 'Card',
        width: '10%',
        sort: tableSort.byString('card')
    },
    {
        key: 'status',
        name: 'Status',
        width: '10%',
        sort: tableSort.byString('status')
    }
];
