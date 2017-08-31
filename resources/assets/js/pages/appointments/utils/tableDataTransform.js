import convertStatus from './convertStatus';
import toLocal from '../../../utils/methods/toLocal';
import { capitalize } from '../../../utils/filters/textformat';

export default function(appointments, zone, userType) {
  return appointments
  .sort((a, b) => new Date(b.attributes.appointment_at.date) - new Date(a.attributes.appointment_at.date))
  .map(obj => {

    // change client name to a profile page hyperlink if admin
    const isAdmin = userType === 'admin';
    const clientName = `${capitalize(obj.patientData.first_name)} ${capitalize(obj.patientData.last_name)}`;
    const clientLink = `<a href="#profile/${obj.patientData.user_id}">${clientName}</a>`;

    const data = {
      date: toLocal(obj.attributes.appointment_at.date, 'dddd, MMMM Do'),
      time: `${toLocal(obj.attributes.appointment_at.date, 'h:mm a')} (${zone})`,
      client: isAdmin ? clientLink : clientName,
      doctor: `Dr. ${obj.attributes.practitioner_name}`,
      status: convertStatus(obj.attributes.status),
      purpose: obj.attributes.reason_for_visit,

      address_1: obj.patientData.address_1,
      address_2: obj.patientData.address_2,
      _appointmentId: obj.id,
      city: obj.patientData.city,
      _date: obj.attributes.appointment_at.date,
      _doctorId: obj.attributes.practitioner_id,
      _patientEmail: obj.patientData.email,
      _patientId: obj.patientData.id,
      _patientFirst: obj.patientData.first_name,
      _patientLast: obj.patientData.last_name,
      _doctorId: obj.attributes.practitioner_id,
      _appointmentId: obj.id,
      _date: obj.attributes.appointment_at.date,
      _duration: obj.attributes.duration_in_minutes,
      _patientPhone: obj.patientData.phone,
      state: obj.patientData.state,
      zip: obj.patientData.zip,
    }
    return {
      data,
      values: [
        data.date,
        data.time,
        data.client,
        data.doctor,
        data.status,
        data.purpose
      ]
    }
  })
}
