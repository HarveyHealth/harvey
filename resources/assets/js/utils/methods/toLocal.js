import moment from 'moment';

export default function (date, format) {
    return moment.utc(date).local().format(format);
}
