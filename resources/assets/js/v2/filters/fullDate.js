import moment from 'moment';

export default function(date) {
    return moment(date).format('dddd, MMMM Do');
}
