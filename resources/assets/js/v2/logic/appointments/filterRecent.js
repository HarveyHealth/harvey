export default function(appointments) {
  return appointments.filter(appointment => {
    const date = appointment.attributes.appointment_at.date;
    return App.Util.time.betweenNowAnd(date, 'subtract', 7, 'days');
  })
}
