import data from './data';
import misc from './misc';
import time from './time';

export default {
    data,
    misc,
    time,

    flattenLists(lists) {
        return lists.reduce((output, list) => output.concat(list));
    },

    removeDuplicates(list) {
        return list.filter((item, i) => i === list.indexOf(item));
    }
};
