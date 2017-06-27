import moment from 'moment';

export default function(fetchedAvailability, userType) {

  if (!fetchedAvailability || !fetchedAvailability.length) return [];

  // Rules:
  // - If day has passed, do not include
  // - If day is current day and userType is patient, check 4 hour buffer
  const bufferHours = 4;
  const buffer = moment.utc().add(bufferHours, 'hours');
  const today = moment();

  const makeTimeObj = iso => {
    return {
      stored: moment(iso).format('YYYY-MM-DD HH:mm:ss'),
      utc: moment.utc(iso),
      local: moment.utc(iso).local(),
    }
  }

  const makeDayObj = iso => {
    return {
      date: moment(iso).format('YYYY-MM-DD'),
      day: moment(iso).format('dddd'),
      times: []
    }
  }

  let day = '';
  let dayObj = null;
  let timeObj = [];
  let availabilityTransformed = [];

  fetchedAvailability.forEach((iso, index) => {
    // Only if the date is pending
    if (today.diff(moment(iso))) {
      const date = moment(iso).format('YYYY-MM-DD');
      if (date !== day) {
        if (dayObj) availabilityTransformed.push(dayObj);
        dayObj = makeDayObj(iso);
        day = date;
      }
      // If user is patient and time is after 4-hour buffer
      if (userType === 'patient' && buffer.diff(moment.utc(iso), 'hours') < 0) {
        dayObj.times.push(makeTimeObj(iso));
      } else if (!userType || userType !== 'patient') {
        dayObj.times.push(makeTimeObj(iso));
      }
    }
  })

  return availabilityTransformed;

}
