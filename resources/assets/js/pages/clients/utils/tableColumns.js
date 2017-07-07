import tableSort from '../../../utils/methods/tableSort';

export default [
  {
    key: 'client',
    name: 'Client',
    width: '12%',
    sort: tableSort.byDate('client')
  },
  {
    key: 'signup',
    name: 'Date',
    width: '12%',
    sort: tableSort.byTime('signup')
  },
  {
    key: 'location',
    name: 'Location',
    width: '12%',
    sort: tableSort.byString('location')
  },
  {
    key: 'booked',
    name: 'Booked',
    width: '5%',
    sort: tableSort.byString('booked')
  },
  {
    key: 'done',
    name: 'Appt Done',
    width: '5%',
    sort: tableSort.byString('done')
  },
  {
    key: 'doctor',
    name: 'Practitioner',
    width: '12%',
    sort: tableSort.byString('doctor')
  },
  {
    key: 'email',
    name: 'Email',
    width: '21%',
    sort: tableSort.byString('email')
  },
  {
    key: 'phone',
    name: 'Phone',
    width: '21%',
    sort: tableSort.byString('phone')
  }
  // {
  //   key: 'intake',
  //   name: 'Intake Form',
  //   width: '5%',
  //   sort: tableSort.byString('intake')
  // }
]
