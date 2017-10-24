import moment from 'moment';

// Returns a boolean after checking if the given date is between now and
// the time constructed from action, length, and units. Assumes all dates
// passed are UTC.
// This uses moment.js for figuring out the time and so the possible
// actions include 'add' or 'subtract'.
// Example:
//    betweenNowAnd(moment().utc(), 'add', 3, 'days') yields true
export default function(date, action, length, units) {
  const _date = moment(App.Util.time.toLocal(date, 'YYYY-MM-DD HH:mm:ss'));
  const test = moment()[action](length, units);

  switch (action) {
    case 'add':
      return _date.diff(moment()) > 0 && test.diff(_date) > 0;
    case 'subtract':
      return _date.diff(moment()) < 0 && _date.diff(test) > 0;
  }
}
