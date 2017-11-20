export default function(appointments) {
  const data = appointments.data;
  const included = appointments.included;

  return data.map(appointment => {
    const userId = App.Util.data.find(included, [
      { where: 'type', is: 'patient' },
      { where: 'id', is: appointment.attributes.patient_id }
    ]).attributes.user_id;
    appointment.user = App.Util.data.find(included, [
      { where: 'type', is: 'user' },
      { where: 'id', is: userId }
    ]);
    return appointment;
  });
}
