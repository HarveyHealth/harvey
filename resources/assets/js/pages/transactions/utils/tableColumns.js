import tableSort from '../../../utils/methods/tableSort';

export default [
  {
    key: 'date',
    name: 'Date',
    width: '15%',
    sort: tableSort.byDate('date')
  },
  {
    key: 'service',
    name: 'Service',
    width: '5%',
    sort: tableSort.byString('service')
  },
  {
    key: 'details',
    name: 'Details',
    width: '15%',
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
    width: '15%',
    sort: tableSort.byString('discount')
  },
  {
    key: 'total',
    name: 'Total',
    width: '15%',
    sort: tableSort.byString('total')
  },
  {
    key: 'card',
    name: 'Card',
    width: '15%',
    sort: tableSort.byString('card')
  }
];
