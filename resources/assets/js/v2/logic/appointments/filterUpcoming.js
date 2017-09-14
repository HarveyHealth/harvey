export default function(appointments) {
  return appointments.filter(appointment => {
    const date = appointment.attributes.appointment_at.date;
    return App.Util.time.betweenNowAnd(date, 'add', 7, 'days');
  })
}
