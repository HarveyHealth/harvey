import tableSort from '../../../utils/methods/tableSort';

export default [
  {
    key: 'client',
    name: 'Client',
    width: '13%',
    sort: tableSort.byDate('client')
  },
  {
    key: 'signup',
    name: 'Date',
    width: '13%',
    sort: tableSort.byTime('signup')
  },
  {
    key: 'location',
    name: 'Location',
    width: '13%',
    sort: tableSort.byString('location')
  },
  {
    key: 'booked',
    name: 'Booked',
    width: '5%',
    sort: tableSort.byString('booked')
  },
  {
    key: 'doctor',
    name: 'Practitioner',
    width: '30%',
    sort: tableSort.byString('doctor')
  },
  {
    key: 'email',
    name: 'Email',
    width: '13%',
    sort: tableSort.byString('email')
  },
  {
    key: 'phone',
    name: 'Phone',
    width: '13%',
    sort: tableSort.byString('phone')
  }
  // {
  //   key: 'intake',
  //   name: 'Intake Form',
  //   width: '5%',
  //   sort: tableSort.byString('intake')
  // },
  // {
  //   key: 'done',
  //   name: 'Appt Done',
  //   width: '30%',
  //   sort: tableSort.byString('done')
  // }
]
