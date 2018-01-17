import moment from 'moment';

export default function(time) {
    return moment(time).utc().format('YYYY-MM-DD HH:mm:ss');
}
