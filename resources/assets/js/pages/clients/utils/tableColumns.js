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
    name: 'Signup',
    width: '12%',
    sort: tableSort.byTime('signup')
  },
  {
    key: 'phone',
    name: 'Phone',
    width: '12%',
    sort: tableSort.byString('phone')
  },
  {
    key: 'email',
    name: 'Email',
    width: '12%',
    sort: tableSort.byString('email')
  },
  {
    key: 'location',
    name: 'Location',
    width: '12%',
    sort: tableSort.byString('location')
  },
  {
    key: 'booked',
    name: 'Appt Booked',
    width: '5%',
    sort: tableSort.byString('booked')
  },
  {
    key: 'doctor',
    name: 'Practitioner',
    width: '12%',
    sort: tableSort.byString('doctor')
  },
  // {
  //   key: 'intake',
  //   name: 'Intake Form',
  //   width: '5%',
  //   sort: tableSort.byString('intake')
  // },
  {
    key: 'done',
    name: 'Appt Done',
    width: '30%',
    sort: tableSort.byString('done')
  }
]
