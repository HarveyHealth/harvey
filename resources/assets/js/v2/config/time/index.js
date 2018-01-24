import moment from 'moment-timezone';

export default {
  zone: moment.tz.guess(),
  zoneAbbr: moment.tz(moment.tz.guess()).format('z'),
}
