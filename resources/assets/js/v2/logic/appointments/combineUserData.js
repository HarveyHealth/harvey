export default function(appointments) {
  const data = appointments.data;
  const included = appointments.included;

  return data.map(appointment => {
    const userId = App.Util.data.find(included, [
      { path: 'type', resolve: 'patients' },
      { path: 'id', resolve: appointment.attributes.patient_id }
    ]).attributes.user_id;
    appointment.user = App.Util.data.find(included, [
      { path: 'type', resolve: 'users' },
      { path: 'id', resolve: userId }
    ])
    return appointment;
  })
}
