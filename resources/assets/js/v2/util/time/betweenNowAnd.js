import moment from 'moment';

export default function(date, action, length, units) {
  const _date = moment(App.Util.toLocalTime(date, 'YYYY-MM-DD HH:mm:ss'));
  const test = moment()[action](length, units);

  switch (action) {
    case 'add':
      return _date.diff(moment()) > 0 && test.diff(_date) > 0;
      break;
    case 'subtract':
      return _date.diff(moment()) < 0 && _date.diff(test) > 0;
      break;
  }
}
