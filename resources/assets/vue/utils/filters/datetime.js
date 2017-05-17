import moment from 'moment';

export default function(value, format) {
    return moment(value).format(format);
}
