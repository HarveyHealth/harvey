import moment from 'moment';

export default function(time, format) {
  return moment(time).format(format);
}
