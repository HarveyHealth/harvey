import data from './data';
import misc from './misc';
import time from './time';

import startFetch from './startFetch';

export default {
    data,
    misc,
    time,

    findObject: data.find,
    getFullName: misc.fullName,
    startFetch
};
