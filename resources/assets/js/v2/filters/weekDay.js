import moment from 'moment';

export default function(date) {
    return moment(date).format('MMM D');
}