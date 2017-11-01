import tableSort from '../../../utils/methods/tableSort';

export default [
  {
    key: 'lab_test',
    name: 'Lab Test',
    width: '16%',
    sort: tableSort.byDate('lab_test')
  },
  {
    key: 'lab_name',
    name: 'Lab Name',
    width: '16%',
    sort: tableSort.byString('lab_name')
  },
  {
    key: 'sample',
    name: 'Sample',
    width: '12%',
    sort: tableSort.byString('sample')
  },
  {
    key: 'description',
    name: 'Description',
    width: '12%',
    sort: tableSort.byTime('description')
  },
  {
    key: 'quote',
    name: 'Quote',
    width: '12%',
    sort: tableSort.byString('quote')
  },
  {
    key: 'image',
    name: 'Image',
    width: '12%',
    sort: tableSort.byString('image')
  },
  {
    key: 'price',
    name: 'Price',
    width: '8%',
    sort: tableSort.byString('price')
  },
  {
    key: 'cost',
    name: 'Cost',
    width: '8%',
    sort: tableSort.byString('cost')
  },
]
