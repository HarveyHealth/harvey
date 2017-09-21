import moment from 'moment-timezone';

export default function (time, zone) {
    return moment.utc(time, 'YY-MM-DD hh:mm:ss').tz(zone);
}
