import moment from 'moment';

export default function(fetchedAvailability, userType) {

  if (!fetchedAvailability || !fetchedAvailability.length) return [];

  // Rules:
  // - If day has passed, do not include
  // - If day is current day and userType is patient, check 4 hour buffer
  const bufferHours = 4;
  const buffer = moment().add(bufferHours, 'hours');
  const today = moment();

  const makeTimeObj = iso => ({
    stored: moment(iso).format('YYYY-MM-DD HH:mm:ss'),
    utc: moment.utc(iso),
    local: moment.utc(iso).local()
  });

  const makeDayObj = iso => ({
    date: moment.utc(iso).format('YYYY-MM-DD'),
    day: moment.utc(iso).format('dddd'),
    times: []
  });

  let day = '';
  let dayObj = null;
  let availabilityTransformed = [];

  fetchedAvailability.forEach((iso, index) => {
    // Only if the date is pending
    if (moment.utc(iso).local().diff(today)) {
      // grab utc and format
      const date = moment(iso).format('YYYY-MM-DD');
      // check if time is after 4-hour buffer zone
      const afterBuffer = moment.utc(iso).local().diff(buffer);

      if (date !== day) {
        if (dayObj) availabilityTransformed.push(dayObj);
        dayObj = makeDayObj(iso);
        day = date;
      }
      // If user is patient and time is not within 4-hour buffer zone
      if (userType === 'patient' && afterBuffer > 0) {
        dayObj.times.push(makeTimeObj(iso));
      } else if (!userType || userType !== 'patient') {
        dayObj.times.push(makeTimeObj(iso));
      }

      if (dayObj && index === fetchedAvailability.length - 1) {
        availabilityTransformed.push(dayObj);
      }

    }
  });

  return availabilityTransformed;

}
