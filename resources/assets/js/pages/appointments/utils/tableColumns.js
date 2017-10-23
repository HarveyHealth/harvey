import tableSort from '../../../utils/methods/tableSort';

export default [
    {
        key: 'date',
        name: 'Date',
        width: '15%',
        sort: tableSort.byDate('_date')
    },
    {
        key: 'time',
        name: 'Time',
        width: '10%',
        sort: tableSort.byTime('_date')
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
        key: 'status',
        name: 'Status',
        width: '10%',
        sort: tableSort.byString('status')
    },
    {
        key: 'purpose',
        name: 'Purpose',
        width: '30%',
        sort: tableSort.byString('purpose')
    }
];
