import moment from 'moment';

const cleanTime = t => {
  return t
    .replace(/( am| pm)$/, ':00')
    .replace(/^(\d\:)/, '0$1');
}

export default {

  byDate(prop) {
    return (a, b) => {
      return moment(a.rowData[prop]).diff(moment(b.rowData[prop]));
    }
  },

  byNumber(prop) {
    return (a, b) => a.rowData[prop] - b.rowData[prop];
  },

  byString(prop) {
    return (a, b) => {
      const A = a.rowData[prop].toUpperCase();
      const B = b.rowData[prop].toUpperCase();
      if (A > B) return 1;
      if (A < B) return -1;
      return 0;
    }
  },

  byTime(prop) {
    return (a, b) => {
      const today = moment().format('YYYY-MM-DD');
      const A = moment(`${today} ${a.rowData[prop].match(/\d\d\:\d\d\:\d\d/)[0]}`);
      const B = moment(`${today} ${b.rowData[prop].match(/\d\d\:\d\d\:\d\d/)[0]}`);
      return A.diff(B);
    }
  }

}
