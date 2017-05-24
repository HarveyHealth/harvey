import moment from 'moment';

export default function(availability) {

  if (!availability || !availability.length) return;

  const weekStart = moment().startOf('week');
  const today = moment().format('dddd');
  const bufferHours = 4;
  const buffer = moment.utc().add(bufferHours, 'hours');

  let times = [], week1 = [], week2 = [];

  const transform = (times, week, start) => {
    for (let i = 0; i <= 6; i++) {
      const date = start.add(1, 'days').format('YYYY-MM-DD');
      times.push({ date: date, day: moment(date).format('dddd') })
    }
    times = times.map(dayObj => {
      dayObj.times = week
        .filter(timeObj => timeObj.day === dayObj.day)
        .map(timeObj => {
          const dateTime = `${dayObj.date} ${timeObj.time}:00`;
          return {
            stored: dateTime,
            utc: moment(dateTime).utc(),
            local: moment.utc(dateTime).local()
          }
        })
      return dayObj;
    })
    return times;
  }

  week1 = transform([], availability[0], weekStart);
  // Remove times in current day that fall within the 4-hour buffer zone
  week1 = week1.map(dayObj => {
    if (dayObj.day === today) {
      dayObj.times = dayObj.times.filter(timeObj => {
        return buffer.diff(moment.utc(timeObj.stored), 'hours') < 0;
      })
    }
    return dayObj;
  })

  week2 = transform([], availability[1], weekStart);
  times = week1.concat(week2);

  return times;

}
