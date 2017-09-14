export default [
  {
    name: 'Date',
    output: data => App.Util.toLocalTime(data.attributes.appointment_at.date, 'ddd, MMM Do')
  },
  {
    name: 'Time',
    output: data => App.Util.toLocalTime(data.attributes.appointment_at.date, 'h:mm a')
  },
  {
    name: 'Client',
    output: data => `${data.user.attributes.first_name} ${data.user.attributes.last_name}`
  },
  {
    name: 'Doctor',
    output: data => `${data.attributes.practitioner_name}`
  },
  {
    name: 'Status',
    output: data => `${App.Fn.convertAppointmentStatus(data.attributes.status)}`
  },
  {
    name: 'Purpose',
    output: data => data.attributes.reason_for_visit
  }
]
