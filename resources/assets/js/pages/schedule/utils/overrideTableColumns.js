import tableSort from '../../../utils/methods/tableSort';


export default [
  {
    key: 'client',
    name: 'Client',
    width: '20%',
    sort: tableSort.byDate('client')
  },
  {
    key: 'signup',
    name: 'Joined',
    width: '20%',
    sort: tableSort.byTime('signup')
  },
  {
    key: 'location',
    name: 'Location',
    width: '20%',
    sort: tableSort.byString('location')
  },
  {
    key: 'doctor',
    name: 'Doctor',
    width: '20%',
    sort: tableSort.byString('doctor')
  },
  {
    key: 'booked',
    name: 'Confirmed',
    width: '10%',
    sort: tableSort.byString('booked')
  },
  {
    key: 'done',
    name: 'Completed',
    width: '10%',
    sort: tableSort.byString('done')
  }
];
