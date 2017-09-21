import moment from 'moment';

export default {

    byDate (prop) {
        return (a, b) => {
            return moment(a.data[prop]).diff(moment(b.data[prop]));
        };
    },

    byNumber (prop) {
        return (a, b) => a.data[prop] - b.data[prop];
    },

    byString (prop) {
        return (a, b) => {
            const A = a.data[prop].toUpperCase();
            const B = b.data[prop].toUpperCase();
            if (A > B) return 1;
            if (A < B) return -1;
            return 0;
        };
    },

    byTime (prop) {
        return (a, b) => {
            const today = moment().format('YYYY-MM-DD');
            const A = moment(`${today} ${a.data[prop].match(/\d\d\:\d\d\:\d\d/)[0]}`);
            const B = moment(`${today} ${b.data[prop].match(/\d\d\:\d\d\:\d\d/)[0]}`);
            return A.diff(B);
        };
    }

};
