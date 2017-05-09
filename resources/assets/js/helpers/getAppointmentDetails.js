// appointmentData = returned from appointment api call
// contains appointment data and included user/patient information
// this function combines the relavant patient data with the appointment details
export default function(appointmentData) {

  const combineAppointmentData = (details) => {
      return details.data.map(appt => {
        appt.patientData = getIncludedPatient(details.included, appt);
        return appt;
      });
  };

  const getIncludedPatient = (_included, _appointment) => {
      const patientId = _appointment.attributes.patient_id;
      const patientData = {
          id: patientId,
      };

      // first, get the patient information from the provided patient_id from appointment
      const relatedPatient = _included.map((item) => {
          if (item.type === 'patients' && item.id === patientData.id.toString()) {
              patientData.user_id = item.attributes.user_id;
          }
      });

      // now find the related user
      const relatedUser = _included.map((item) => {
          // needed since the data types are different
          if (item.type === 'users' && item.id === patientData.user_id.toString()) {
              patientData.first_name = item.attributes.first_name;
              patientData.last_name = item.attributes.last_name;
              patientData.email = item.attributes.email;
              patientData.phone = item.attributes.phone;
          }
      });

      return patientData;
  };

  return combineAppointmentData(appointmentData);

}
