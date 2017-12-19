import moment from 'moment';

export default function(date) {
    return moment(date)
      .format('h:mm a')
      .replace(/[m ]*/g,'')
      .replace(/:00/,'');
}
