import convertStatus from './convertStatus';
import toLocal from '../../utils/methods/toLocal';

export default function(appointments) {
  return appointments.map(obj => {
    const rowData = {
      date: toLocal(obj.attributes.appointment_at.date, 'dddd, MMM Do'),
      time: toLocal(obj.attributes.appointment_at.date, 'h:mm a'),
      client: `${obj.patientData.first_name} ${obj.patientData.last_name}`,
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
      rowData,
      rowValues: [
        rowData.date,
        rowData.time,
        rowData.client,
        rowData.doctor,
        rowData.status,
        rowData.purpose
      ]
    }
  })
}
