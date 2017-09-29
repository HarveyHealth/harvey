import tableSort from '../../../utils/methods/tableSort';

export default [
  {
    key: 'order_date',
    name: 'Date',
    width: '15%',
    sort: tableSort.byDate('_date')
  },
  {
    key: 'client',
    name: 'Client',
    width: '15%',
    sort: tableSort.byString('client')
  },
  {
    key: 'doctor',
    name: 'Doctor',
    width: '20%',
    sort: tableSort.byString('doctor')
  },
  {
    key: 'order_id',
    name: 'Order Id',
    width: '10%',
    sort: tableSort.byTime('_date')
  },
  {
    key: 'tests',
    name: 'Lab Tests',
    width: '10%',
    sort: tableSort.byString('status')
  },
  {
    key: 'status',
    name: 'Status',
    width: '30%',
    sort: tableSort.byString('purpose')
  },
]
