import convertStatus from './convertStatus';
import toLocal from '../../utils/methods/toLocal';
import { capitalize } from '../../utils/filters/textformat';

export default function(appointments) {
  return appointments.map(obj => {
    const data = {
      date: toLocal(obj.attributes.appointment_at.date, 'dddd, MMM Do'),
      time: toLocal(obj.attributes.appointment_at.date, 'h:mm a'),
      client: `${capitalize(obj.patientData.first_name)} ${capitalize(obj.patientData.last_name)}`,
      doctor: `Dr. ${obj.attributes.practitioner_name}`,
      status: convertStatus(obj.attributes.status),
      purpose: obj.attributes.reason_for_visit,

      _patientId: obj.patientData.id,
      _patientEmail: obj.patientData.email,
      _patientPhone: obj.patientData.phone,
      _patientFirst: obj.patientData.first_name,
      _patientLast: obj.patientData.last_name,
      _doctorId: obj.attributes.practitioner_id,
      _appointmentId: obj.id,
      _date: obj.attributes.appointment_at.date
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
